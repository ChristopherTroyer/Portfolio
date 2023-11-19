""" /********************************************************************
Class:     CSCI 652/490
Program:   Initial Results of project
Authors:   Kleo, Chris 

Purpose:   Calculating and plotting distance matrices

Execution: python projectMain.py

*********************************************************************/ """

from Bio import AlignIO
from Bio import Align
from Bio import Phylo
from Bio.Phylo.TreeConstruction import *
from Bio.Seq import Seq
from Bio.SeqRecord import SeqRecord
from Bio.Align import MultipleSeqAlignment

distance_matrix = None


#### MANUAL ALIGNMENT TEST STUFF
# sarsPath = "FinalProject\ProjectData\seqs\zeta"
# sars2Path = "FinalProject\ProjectData\seqs\sars2"
# sarsData = ""
# sars2Data = ""

# with open(sarsPath) as f:
#     next(f)
#     sarsData = "".join(line.rstrip() for line in f)
    
# print("data 1 read")
# with open(sars2Path) as f:
#     next(f)
#     sars2Data = "".join(line.rstrip() for line in f)
# print("data 2 read")

# aligner = Align.PairwiseAligner()
# aligner.open_gap_score = -10
# aligner.extend_gap_score = -0.5
# #aligner.mode = 'global'
# aligner.substitution_matrix = substitution_matrices.load("BLOSUM62")

#alignments = aligner.align(sarsData,sars2Data)

#alignment = alignments[0]
#print(alignment.score)
#print(alignment)


# for alignment in sorted(alignments):
#     print("Score = %.1f:" % alignment.score)
#     print(alignment)

# input("done")

calc = DistanceCalculator('identity') #could also use 'genetic'
for record in AlignIO.parse("FinalProject\ProjectData\multi\sars2VariantsAll.multiz.maf", "maf"):
    if record.get_alignment_length() > 100:
        print(record)
        distance_matrix = calc.get_distance(record)
        print(distance_matrix)

# Build the tree
constructor = DistanceTreeConstructor()
tree = constructor.nj(distance_matrix)

#tree.ladderize() sorts the tree branches by length

# Draw the tree
Phylo.draw(tree)
