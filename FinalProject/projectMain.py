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
from matplotlib import pyplot
from Bio.Phylo.Consensus import *

def sciNota(inputNum):
    return "{:e}".format(inputNum)

def innerKiller(inputStr):
    if(inputStr.name.find("Inner") == -1):
        return inputStr
    else:
        return ""

#### MANUAL ALIGNMENT TEST STUFF
# sarsPath = "FinalProject\ProjectData\seqs\zeta"
# sars2Path = "FinalProject\ProjectData\seqs\mers"
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

# alignments = aligner.align(sarsData,sars2Data)

# alignment = alignments[0]
# print(alignment.score)
# print(alignment)


# for alignment in sorted(alignments):
#     print("Score = %.1f:" % alignment.score)
#     print(alignment)

# input("done")

distance = None

calc = DistanceCalculator('genetic') #could also use 'identity'
filePath = "FinalProject\ProjectData\multi\sars2VariantsAll.multiz.maf"
fileName = filePath[filePath.rfind("\\")+1:]

trees = list()
constructor = DistanceTreeConstructor(calc)


for record in AlignIO.parse(filePath, "maf"):
    if record.get_alignment_length() > 500:
        print(record)
        distance_matrix = calc.get_distance(record)
        print(distance_matrix)

tree = constructor.nj(distance_matrix) #distance_matrix

# Build the tree
#tree = constructor.nj(distance_matrix) #distance_matrix

tree.ladderize() #sorts the tree branches by length

#pyplot.xkcd() funny

#pyplot.rc('axes', labelsize=0)
fig = pyplot.figure(figsize=(30, 20), dpi=300)
axes = fig.add_subplot(1, 1, 1)

tree.root.color = "gray"
# Draw the tree
Phylo.draw(tree, branch_labels=(lambda c:c.branch_length), label_func=(lambda x: innerKiller(x)), title=(fileName+" Neighbor Joining", None, 'center', None),axes=axes, do_show=False)
pyplot.savefig(fileName+"_NeighborJoining.png",bbox_inches='tight', dpi=300)


pyplot.cla()

tree = constructor.upgma(distance_matrix) #distance_matrix
tree.ladderize() #sorts the tree branches by length
tree.root.color = "gray"
pyplot.rc('axes', labelsize=0)

# Draw the tree
Phylo.draw(tree, branch_labels=(lambda c:c.branch_length), label_func=(lambda x: innerKiller(x)), title=(fileName+" UPGMA", None, 'center', None),axes=axes, do_show=False)
pyplot.savefig(fileName+"_UPGMA.png",bbox_inches='tight', dpi=300)