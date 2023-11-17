from Bio import AlignIO
from Bio.Phylo.TreeConstruction import DistanceCalculator
from Bio.Seq import Seq
from Bio.SeqRecord import SeqRecord
from Bio.Align import MultipleSeqAlignment

calc = DistanceCalculator('genetic')
for record in AlignIO.parse("FinalProject\ProjectData\multi\sars2Variants.multiz.maf", "maf"):
    if record.get_alignment_length() > 40:
        print(record)
        #calc = DistanceCalculator('identity')
        dm = calc.get_distance(record)
        print(dm)



#print(multiArr)
#calc = DistanceCalculator('identity')
#dm = calc.get_distance(file)
#print(dm)