from Bio import Phylo
from Bio.Phylo.TreeConstruction import DistanceMatrix, DistanceTreeConstructor

# Example distance matrix with integers as names
distance_matrix_data = [
    [0.0],
    [0.2, 0.0],
    [0.4, 0.5, 0.0],
    [0.7, 0.8, 0.6, 0.0],
    [0.9, 1.0, 0.8, 0.3, 0.0],
    [0.1, 0.2, 0.3, 0.5, 0.4, 0.0],
    [0.3, 0.4, 0.5, 0.7, 0.6, 0.2, 0.0],
    [0.6, 0.7, 0.8, 1.0, 0.9, 0.5, 0.3, 0.0],
    [0.8, 0.9, 1.0, 0.2, 0.1, 0.7, 0.5, 0.2, 0.0],
    [0.5, 0.6, 0.7, 0.9, 0.8, 0.4, 0.2, 0.9, 0.7, 0.0]
]

# Convert indices to strings
genome_names = list(map(lambda x: f'A{x}', range(1, 11)))

# Create a DistanceMatrix object
distance_matrix = DistanceMatrix(genome_names, matrix=distance_matrix_data)

# Build the tree
constructor = DistanceTreeConstructor()
tree = constructor.nj(distance_matrix)

# Draw the tree
Phylo.draw(tree)
