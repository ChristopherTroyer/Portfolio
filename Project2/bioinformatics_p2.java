/********************************************************************
Class:     CSCI 652/490
Program:   Project 2
Authors:   Kleo, Chris 

Purpose:   Analysis of substitution in genome comparison. 

Execution: java bioinformatics_p2 > output.csv
    or     java bioinformatics_p2 "path/to/your/file.maf" > output.csv

*********************************************************************/

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.Arrays;

public class bioinformatics_p2 {
    public static void main(String[] args) {
        
        String filename = "";
        if (args.length == 0)
        {
            filename = "/home/turing/mhou/data/human.chimpanzee.chr22.maf"; // Replace with the actual file path
        }
        else
        {
            filename = args[0];
        }

        int[][] totalCharacterCounts = new int[5][5]; // Initialize a 5x5 matrix for character counts
        // Row 0: 'A' in seq1, Row 1: 'T' in seq1, Row 2: 'C' in seq1, Row 3: 'G' in seq1, Row 4: '-' in seq1

        int[] gapSizeCount = new int[105];
        Arrays.fill(gapSizeCount, 0);
        int gapCount = 0;


        try (BufferedReader br = new BufferedReader(new FileReader(filename))) {
            String line;
            String prevLine = null;
            int lineCount = 0;
            boolean shouldCompare = false;

            while ((line = br.readLine()) != null) {
                lineCount++;

                if (lineCount > 3) {
                    // Only start comparing from line 4 onwards
                    shouldCompare = true;

                    if (shouldCompare) {
                        if (prevLine != null && line.length() >= 38 && prevLine.length() >= 38) {
                            // Extract sequences starting from the 38th position
                            String sequence1 = prevLine.substring(37).toLowerCase(); // Convert to lowercase
                            String sequence2 = line.substring(37).toLowerCase(); // Convert to lowercase

                            countMatchingCharacters(sequence1, sequence2, totalCharacterCounts, gapSizeCount);
                        }
                    }
                }

                prevLine = line;
            }
        } catch (IOException e) {
            e.printStackTrace();
        }

        // Print the total character counts at the end

        //System.out.println("Total character counts:");
        //printCharacterCounts(totalCharacterCounts);

        //calculate substitution rate

        int matches = totalCharacterCounts[0][0] + totalCharacterCounts[1][1] + totalCharacterCounts[2][2] + totalCharacterCounts[3][3];
        int transversions = totalCharacterCounts[0][1] + totalCharacterCounts[0][2] + totalCharacterCounts[1][0] + totalCharacterCounts[2][0]
        + totalCharacterCounts[1][3] + totalCharacterCounts[2][3] + totalCharacterCounts[3][1] + totalCharacterCounts[3][2];
        int transitions = totalCharacterCounts[0][3] + totalCharacterCounts[3][0] + totalCharacterCounts[1][2] + totalCharacterCounts[2][1];
        //System.out.println("matches: " + matches);
        //System.out.println("Transitions: " + transitions);
        //System.out.println("Transversions: " + transversions);

        int mismatch = transitions + transversions;
        //float subRate = ((float)mismatch / (mismatch + matches));
        //System.out.println("\nSubstitution rate: " + subRate);
        //float trRate =  (float)transitions/transversions;
        //System.out.println("\ntransitions vs transversions ratio: " + trRate);
        int gapsum = sumTotalGaps(gapSizeCount);
        float gapRate = (float)gapsum/(matches+mismatch+gapsum);

        printGapCounts(gapSizeCount);
        System.out.println(",," + gapsum + "," + gapRate);
    }

    // Function to count characters at exact corresponding positions
    private static void countMatchingCharacters(String seq1, String seq2, int[][] counts, int[] gapSizeCount) {
        if (seq1.length() != seq2.length()) {
            return; // Sequences must have the same length
        }
        int rowGapLength = 0;
        int colGapLength = 0;

        for (int i = 0; i < seq1.length(); i++) {
            char charSeq1 = seq1.charAt(i);
            char charSeq2 = seq2.charAt(i);

            int row = -1;
            int col = -1;

            switch (charSeq1) {
                case 'a':
                case 'A':
                    row = 0;
                    break;
                case 't':
                case 'T':
                    row = 1;
                    break;
                case 'c':
                case 'C':
                    row = 2;
                    break;
                case 'g':
                case 'G':
                    row = 3;
                    break;
                case '-':
                    row = 4;
                    break;
            }

            switch (charSeq2) {
                case 'a':
                case 'A':
                    col = 0;
                    break;
                case 't':
                case 'T':
                    col = 1;
                    break;
                case 'c':
                case 'C':
                    col = 2;
                    break;
                case 'g':
                case 'G':
                    col = 3;
                    break;
                case '-':
                    col = 4;
                    break;
            }

            if (row != -1 && col != -1) {
                counts[row][col]++; // Increment the count for the corresponding character pair
            }

            if (row == 4)
            {
                
                //check for end of gap:
                if (seq1.charAt(i+1) != '-')
                {
                    rowGapLength++;

                    gapSizeCount[rowGapLength]++;

                    rowGapLength = 0;

                }
                else
                {
                    rowGapLength++;
                }
            }

            if (col == 4)
            {
                
                //check for end of gap:
                if (seq2.charAt(i+1) != '-')
                {
                    colGapLength++;

                    gapSizeCount[colGapLength]++;

                    colGapLength = 0;

                }
                else
                {
                    colGapLength++;
                }
            }
        }
    }

    // Function to print character counts
    private static void printCharacterCounts(int[][] counts) {
        char[] characters = {'A', 'T', 'C', 'G', '-'};
        for (int i = 0; i < counts.length; i++) {
            System.out.print(characters[i] + " : ");
            for (int j = 0; j < counts[i].length; j++) {
                System.out.print(characters[j] + " -> " + counts[i][j] + "  ");
            }
            System.out.println();
        }
    }
    // prints out table for 
    private static void printGapCounts(int[] gapList)
    {
        System.out.println("Gap Length (Bases), Gap Count, total gap count, gap rate");
        int lineNum = 0;
        for (int i : gapList)
        {
            if (i != 0)
            {
                System.out.println(lineNum + ", " +i + ",,");
            }
            lineNum++;
        }
    }

    //Sums up an array, 
    private static int sumTotalGaps(int[] gapList)
    {
        int retVal = 0;

        for (int i : gapList)
        {
            retVal = retVal + i;
        }

        return retVal;
    }
}

