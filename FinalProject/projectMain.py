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
from Bio import SeqIO
from Bio.Phylo.TreeConstruction import *
from Bio.Seq import Seq
from Bio.SeqRecord import SeqRecord
from Bio.Align import MultipleSeqAlignment
from matplotlib import pyplot
from ete3 import Tree, TreeStyle, TextFace, NodeStyle

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

testRecord = None
for record in AlignIO.parse(filePath, "maf"):
    if record.get_alignment_length() > 500:
        print(record)
        distance_matrix = calc.get_distance(record)
        print(distance_matrix)
        testRecord = record



tree = constructor.nj(distance_matrix) #distance_matrix

#pScorer = ParsimonyScorer()
#pSearcher = NNITreeSearcher(pScorer)
#pConstructor = ParsimonyTreeConstructor(pSearcher)
#pars_tree = pConstructor.build_tree(testRecord)



# # Build the tree
#tree = constructor.nj(distance_matrix) #distance_matrix


# #pyplot.xkcd() funny

tree.ladderize() #sorts the tree branches by length

#pars_tree.ladderize()

#pyplot.rc('axes', labelsize=0)
##fig = pyplot.figure(figsize=(30, 20), dpi=300)
##axes = fig.add_subplot(1, 1, 1)
##tree.root.color = "gray"
##pars_tree.root.color = "gray"

Phylo.write(tree, fileName+".nwk", "newick")
t = Tree(fileName+".nwk",  format=1)

t.get_tree_root().unroot()

ts = TreeStyle()
ts.mode = "c"
ts.show_leaf_name = True
ts.show_branch_length = True
ts.show_branch_support = True
#ts.show_border = True

ts.scale = 20
ts.title.add_face(TextFace(fileName+" Neighbor Joining", fsize=20), column=0)


#t.show(tree_style=ts)
t.render(fileName+"_NJ_new.svg", tree_style=ts)
t.render(fileName+"_NJ_new.png", tree_style=ts)




#Phylo.draw(pars_tree, title=(fileName+" Fitch's algorithm", None, 'center', None),branch_labels=(lambda c:c.branch_length),label_func=(lambda x: innerKiller(x)), do_show=False,axes=axes)

# tree.root.color = "gray"
# # Draw the tree //branch_labels=(lambda c:c.branch_length)
# Phylo.draw(tree,branch_labels=(lambda c:c.branch_length), label_func=(lambda x: innerKiller(x)), title=(fileName+" Neighbor Joining", None, 'center', None),axes=axes, do_show=False)

#pyplot.savefig(fileName+"_Parsimony.png",bbox_inches='tight', dpi=300)
#pyplot.savefig(fileName+"_Parsimony.svg",bbox_inches='tight', dpi=300)

#pyplot.cla() #Clears figure to make another with different content
#Phylo.draw(pars_tree, title=(fileName+" Fitch's algorithm", None, 'center', None),label_func=(lambda x: innerKiller(x)), do_show=False,axes=axes)
#pyplot.savefig(fileName+"_Parsimony_noLabels.png",bbox_inches='tight', dpi=300)

# pyplot.cla()

# tree = constructor.upgma(distance_matrix) #distance_matrix
# tree.ladderize() #sorts the tree branches by length
# tree.root.color = "gray"
# pyplot.rc('axes', labelsize=0)

# # Draw the tree
# Phylo.draw(tree,branch_labels=(lambda c:c.branch_length), label_func=(lambda x: innerKiller(x)), title=(fileName+" UPGMA", None, 'center', None),axes=axes, do_show=False)
# pyplot.savefig(fileName+"_UPGMA_Vector.svg",bbox_inches='tight', dpi=300)