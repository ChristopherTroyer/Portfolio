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
#include "cpu_single_hart.h"

void cpu_single_hart::run(uint64_t exec_limit)
{
    regs.set(2, mem.get_size());

    if (exec_limit == 0)
    {
        while (!is_halted())
        {
            tick();
        }
    }
    else
    {
        while (!is_halted() && exec_limit > 0)
        {
            tick();
            exec_limit--;
        }
    }
    if (is_halted())
    {
        std::cout << "Execution terminated. Reason: " << get_halt_reason() << std::endl;        
    }

    std::cout << get_insn_counter() << " instructions executed" << endl;
}