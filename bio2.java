
import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

public class bio2 {
    public static void main(String[] args) {
        String filePath = "/home/hopper/z1940978/human.rat.chr22.maf"; // Replace with your input file path
        int positionToCompare = 38; // Start comparison from position 38
        int pairCount = 0; // Initialize the pair count
        int lineCount = 0; // Initialize the line count

        try (BufferedReader reader = new BufferedReader(new FileReader(filePath))) {
            String line;
            String sequence1 = null;
            String sequence2 = null;

            while ((line = reader.readLine()) != null) {
                lineCount++;
                if (lineCount > 3) { // Skip the first three lines
                    if (line.length() >= positionToCompare) { // Check line length
                        if (sequence1 == null) {
                            sequence1 = line.substring(positionToCompare);
System.out.println("sequence 1 -> "+sequence1);
                        } else {
                            sequence2 = line.substring(positionToCompare);
System.out.println("sequence 2 -> "+sequence2);

                            // Check if the sequences match
                            if (matchSequences(sequence1, sequence2)) {
                                pairCount++;
                            }

                            sequence1 = sequence2;
                        }
                    }
                }
            }
        } catch (IOException e) {
            e.printStackTrace();
        }

        System.out.println("Matching sequence pairs: " + pairCount);
    }

    // Function to check if sequences match 
    public static boolean matchSequences(String sequence1, String sequence2) {
        if (sequence1.length() != sequence2.length()) {
            return false; // Sequences must have the same length to match
        }

        for (int i = 0; i < sequence1.length(); i++) {
            char char1 = sequence1.charAt(i);
            char char2 = sequence2.charAt(i);

            // Check if character in sequence1 is "T" and character in sequence2 is also "T"
            // OR character in sequence1 is "A" and character in sequence2 is "G"
            if ((char1 == 'T' && char2 == 'T') || (char1 == 'A' && char2 == 'G')) {
                continue; // Characters match, continue checking
            } else {
                return false; // Characters don't match, sequences don't match
            }
        }

        return true; // All characters match
    }
}



