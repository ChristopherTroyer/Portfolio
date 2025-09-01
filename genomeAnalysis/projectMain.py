""" /********************************************************************
Class:     CSCI 652/490
Program:   Final project
Authors:   Kleo, Chris 

Purpose:   Creates various phylogenetic tree plots using different algorithms

Execution: python projectMain.py [-h] (-n | -u | -p | -a) [-ns] [--i I] [-o {png,svg,all}]
options:
  -h, --help            show this help message and exit
  -n, -nj               Use Neighbor joining algorithm on input.
  -u, -upgma            Use UPGMA algorithm on input.
  -p, -parsimony        Use Fitch's algorithm on input.
  -a, -all              Use all three algorithms on input.
  -ns, -nosave          Do not save an image file of output tree, instead display interactive tree window.
  --i I, --input I      Path to .maf file, defaults to 'ProjectData/multi/omicronVariants.multiz.maf'
  -o {png,svg,all}, -output {png,svg,all}
                        File type to output images as.
*********************************************************************/ """

import argparse, os
from Bio import AlignIO
from Bio import Phylo
from Bio.Phylo.TreeConstruction import *
from matplotlib import pyplot
from ete3 import Tree, TreeStyle, TextFace

parser = argparse.ArgumentParser()
mutexGroup = parser.add_mutually_exclusive_group(required=True)

mutexGroup.add_argument("-n", "-nj", help="Use Neighbor joining algorithm on input.", action='store_true')
mutexGroup.add_argument("-u", "-upgma", help="Use UPGMA algorithm on input.",action='store_true')
mutexGroup.add_argument("-p", "-parsimony", help="Use Fitch's algorithm on input.",action='store_true')
mutexGroup.add_argument("-a", "-all", help="Use all three algorithms on input.",action='store_true')

parser.add_argument("-ns", "-nosave", help="Do not save an image file of output tree, instead display interactive tree window.",action='store_false')
parser.add_argument("--i", "--input", help="Path to .maf file, defaults to 'ProjectData/multi/omicronVariants.multiz.maf'") #required=True
parser.add_argument("-o", "-output", choices=['png', 'svg', 'all'], default='png', help="File type to output images as.")

args = parser.parse_args()

#Force input number to display in scientific notation
def sciNota(inputNum):
    return "{:e}".format(inputNum)

#Used while printing clade names in Biopython trees to remove spammy "inner" clades
def innerKiller(inputStr):
    if(inputStr.name.find("Inner") == -1):
        return inputStr
    else:
        return ""

#renders trees for UPGMA and parsimony algorithms using biopython
def renderTree(tree, fileName, save, title):
    titles = ["Parsimony", "UPGMA"]
    pyplot.rc('axes', labelsize=0)
    fig = pyplot.figure(figsize=(30, 20), dpi=300)
    axes = fig.add_subplot(1, 1, 1)
    tree.root.color = "gray"
    Phylo.draw(tree,branch_labels=(lambda c:c.branch_length), label_func=(lambda x: innerKiller(x)), title=(fileName+"_"+titles[title], None, 'center', None),axes=axes, do_show=not save)
    if save:
        if args.o == 'png' or args.o == 'all':
            pyplot.savefig(fileName+"_"+titles[title]+".png",bbox_inches='tight', dpi=300)
        if  args.o == 'svg' or args.o == 'all':
            pyplot.savefig(fileName+"_"+titles[title]+".svg",bbox_inches='tight', dpi=300)

#renders neighbor joining tree using ETE
def renderNjTree(tree,fileName,save):
    Phylo.write(tree, fileName+".nwk", "newick")
    t = Tree(fileName+".nwk",  format=1)

    t.get_tree_root().unroot()
    ts = TreeStyle()
    ts.mode = "c"
    ts.show_leaf_name = True
    ts.show_branch_length = True
    ts.show_branch_support = True

    ts.scale = 20
    ts.title.add_face(TextFace(fileName+" Neighbor Joining", fsize=20), column=0)
    if save:
        if args.o == 'png' or args.o == 'all':
            t.render(fileName+"_NJ_new.png", tree_style=ts)
        if  args.o == 'svg' or args.o == 'all':
            t.render(fileName+"_NJ_new.svg", tree_style=ts)
    else:
        t.show(tree_style=ts)
        
    os.remove(fileName+".nwk")

def main():
    filePath = "ProjectData\multi\omicronVariants.multiz.maf" #default input for quick debugging
    
    #handle file input
    if args.i != None:
        if os.path.isfile(args.i):
            if args.i[-4:] == '.maf':
                filePath = args.i
            else:
                print("File not of type .maf")
                return
        else:
            print("File path not valid")
            return
  
    calc = DistanceCalculator('genetic') #could also use 'identity', many models to pick from
    fileName = filePath[filePath.rfind("\\")+1:]
    tree = None
    pars_tree = None
    constructor = DistanceTreeConstructor(calc)
    parsimonyRecord = None
    
    for record in AlignIO.parse(filePath, "maf"):
        if record.get_alignment_length() > 500: #arbitrary limit of 500
            print(record)                       #biopython's functions don't like handling more than one record from the file
            distance_matrix = calc.get_distance(record)
            print(distance_matrix)
            parsimonyRecord = record
    
    if args.n or args.a:
        print("NJ Tree render")
        tree = constructor.nj(distance_matrix)
        tree.ladderize() #sorts the tree branches by length
        renderNjTree(tree, fileName, args.ns)
    
    if args.u or args.a:
        print("UPGMA Tree render")
        tree = constructor.upgma(distance_matrix)
        tree.ladderize()
        renderTree(tree, fileName, args.ns, 1)
    
    if args.p or args.a:
        print("Parsimony Tree render")
        
        pScorer = ParsimonyScorer()
        pSearcher = NNITreeSearcher(pScorer)
        pConstructor = ParsimonyTreeConstructor(pSearcher)
        pars_tree = pConstructor.build_tree(parsimonyRecord)
        
        pars_tree.ladderize()
        renderTree(pars_tree, fileName, args.ns, 0)

if __name__ == "__main__":
    main()