//******************************************************************
//
// CSCI 463 Assignment 5
//
// Author: Christopher Troyer
// Id: Z1945059
// Course section:  CSCI463-1
//
// RISC-V Simulator
//
//  I certify that this is my own work and where appropriate an extension 
//  of the starter code provided for the assignment.
//******************************************************************
#include "hex.h"
#include <iostream>
#include <sstream>
#include <iomanip>
#include <getopt.h>
#include "rv32i_decode.h"
#include "memory.h"
#include "rv32i_hart.h"
#include "cpu_single_hart.h"
#include "registerfile.h"

using std::cerr;
using std::cout;
using std::endl;

/**
 * @brief prints example of proper usage of the program and it's arguments
 * 
 */
static void usage()
{
	cerr << "Usage: rv32i [-dirz] [-m hex-mem-size] [-l execution-limit] infile" << endl;
	cerr << "    -d Show memory disassembly before running simulation." << endl;
	cerr << "    -i Print instructions during execution." << endl;
	cerr << "    -r show dump of hart before each instruction is simulated." << endl;
	cerr << "    -z show dump of hart status and memory after simulation is haulted." << endl;
	cerr << "    -m specify memory size (default = 0x100)" << endl;
	cerr << "    -l maximum limit of instructions to execute. (default = 0 (no limit))" << endl;
	exit(1);
}

/**
 * @brief loops through memory and decodes each 32-bit instruction
 * 
 * @param mem current system memory
 */
static void disassemble(const memory &mem)
{
	//for each 32 bit word in mem decode that address
	for (uint32_t i = 0; i < mem.get_size(); i += 4)
	{
		cout << std::setfill('0') << std::setw(8) << std::hex << i << ": "; //Address
		cout << std::setfill('0') << std::setw(8) << std::hex << mem.get32(i); //instruction
		cout << "  " << rv32i_decode::decode(i,mem.get32(i)) << endl;		//rendered instruction
	}
}

/**
 * @brief Tester for hex and memory programs
 * 
 * @param argc Argument count
 * @param argv Argument Vector
 * @return int Status
 */
int main(int argc, char **argv)
{
	uint32_t memory_limit = 0x100;	// default memory size is 0x100
	uint64_t exec_limit = 0;        // default to no limit

	bool d_flag = false;
	bool i_flag = false;
	bool r_flag = false;
	bool z_flag = false;


	int opt;
	while ((opt = getopt(argc, argv, "m:dil:rz")) != -1)
	{
		switch(opt)
		{
		case 'd':
			{
				d_flag = true;
				break;
			}
		case 'i':
			{
				i_flag = true;
				break;
			}
		case 'l':
			{
				std::istringstream iss(optarg);
				iss >> exec_limit;
				break;
			}
		case 'r':
			{
				r_flag = true;
				break;
			}
		case 'z':
			{
				z_flag = true;
				break;
			}
		case 'm':
			{
				std::istringstream iss(optarg);
				iss >> std::hex >> memory_limit;
			}
			break;

		default: /* ’?’ */
			usage();
		}
	}

	if (optind >= argc)
		usage();	// missing filename

	memory mem(memory_limit);

	if (!mem.load_file(argv[optind]))
		usage();

	if (d_flag)
	{
		disassemble(mem);
	}

	cpu_single_hart cpu(mem);
	cpu.reset();

	cpu.set_show_instructions(i_flag);
	cpu.set_show_registers(r_flag);
	cpu.run(exec_limit);

	if (z_flag)
	{
		//dump hart after simulation halt
		cpu.dump();
		mem.dump();
	}
	
	return 0;
}
