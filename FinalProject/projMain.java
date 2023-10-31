/********************************************************************
Class:     CSCI 652/490
Program:   Final Project Proposal
Authors:   Kleo, Chris 

Purpose: Generate a distance matrix for input files to be fed into a tree construction program
i.e. http://www.trex.uqam.ca/index.php?action=trex&menuD=1&method=2

Execution: java main > output.csv
    or     java main "path/to/your/file.maf" > output.csv

*********************************************************************/

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.Arrays;


public class projMain {
    static int[][] totalCharacterCounts = new int[5][5]; // Initialize a 5x5 matrix for character counts
    // Row 0: 'A' in seq1, Row 1: 'T' in seq1, Row 2: 'C' in seq1, Row 3: 'G' in seq1, Row 4: '-' in seq1

    static int[] gapSizeCount = new int[105];
    static String filename1;
    static String filename2;
    // A T C G -
    //score matrix
    //    A    C    G    T
    // A  91  -114  -31 -123
    // C -114  100 -125 -31
    // G -31  -125  100 -114
    // T -123  -31 -114   91

    static int indel = 0; //400 open score 30 for extend
    static int[][] scoreMatrix = 
    {

     {91,-123,-114,-31, indel},
     {-123,91,-31,-114, indel},  //last is indel
     {-114,-31,100,-125, indel},
     {-31,-114,-125,100, indel},
     {indel,indel,indel,indel,indel}
    };

    public static void main(String[] args)
    {
        Arrays.fill(gapSizeCount, 0);
        filename1 = "";
        filename2 = "";
        if (args.length == 0)
        {
            //filename = "/home/turing/mhou/data/human.chimpanzee.chr22.maf"; // Replace with the actual file path
            System.out.println("program requires 2 file arguments");
        }
        else
        {
            try 
            {
                filename1 = args[0];
            } catch (Exception e) {
                e.printStackTrace();
            }
        }

        readFile(filename1);
        printCharacterCounts(totalCharacterCounts);
        System.out.println("score: " + scoreCharacterCounts(totalCharacterCounts, gapSizeCount));
        printGapCounts(gapSizeCount);
        System.out.println("sum of gaps: " + sumTotalGaps(gapSizeCount));
    }

    private static void readFile(String filename)
    {
        try (BufferedReader br = new BufferedReader(new FileReader(filename))) {
            String line;
            String prevLine = null;
            int lineCount = 0;
            boolean shouldCompare = false;

            while ((line = br.readLine()) != null) {
                lineCount++;

                if (lineCount > 4 && !line.startsWith("#")) {
                    // Only start comparing from line 4 onwards
                    shouldCompare = true;

                    if (shouldCompare) {
                        if (prevLine != null && line.length() >= 50 && prevLine.length() >= 50) {
                            // Extract sequences starting from the 43th position
                            System.out.println("reading line: " + lineCount);



                            String sequence1 = prevLine.substring(prevLine.lastIndexOf(" ")+1).toLowerCase(); // Convert to lowercase
                            String sequence2 = line.substring(line.lastIndexOf(" ")+1).toLowerCase(); // Convert to lowercase

                            System.out.print(prevLine.substring(0, prevLine.lastIndexOf(" ")) + '\n');
                            //System.out.println(sequence1);
                            System.out.print(line.substring(0, line.lastIndexOf(" ")) + '\n');
                            //System.out.println(sequence2);

                            countMatchingCharacters(sequence1, sequence2, totalCharacterCounts, gapSizeCount);
                            break;
                        }
                    }
                }

                prevLine = line;
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
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
        int lineNum = 0;
        for (int i : gapList)
        {
            retVal = retVal + i*lineNum;
            lineNum++;
        }

        return retVal;
    }

    private static int scoreCharacterCounts(int [][] counts, int[] gapList)
    {
        int score = 0;

            for (int i = 0; i < counts.length; i++) {
                //System.out.print(characters[i] + " : ");
                for (int j = 0; j < counts[i].length; j++) {
                    score = score + (counts[i][j] * scoreMatrix[i][j]);
                    //System.out.println(counts[i][j] + " times " + scoreMatrix[i][j]);
                    //System.out.println(i + " " + j);
                    }      
                }

        //calculate gap score
        int lineNum = 0;
        for (int i : gapList)
        {
            if (i != 0)
            {
                if (lineNum == 1) {
                    score = score - 400;
                }
                else
                {
                    score = score - (400 + ((lineNum-1)*30));
                }
            }
            lineNum++;
        }            

        return score;
    }
}