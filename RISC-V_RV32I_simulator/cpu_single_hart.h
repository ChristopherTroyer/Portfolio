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
#ifndef S_HART_H
#define S_HART_H
#include "rv32i_hart.h"

/**
 * Simulation of a single hart RISC-V CPU
 */
class cpu_single_hart : public rv32i_hart
{
    public:
        /**
        * @brief inherits a single cpu from rv32i_hart
         * @param mem Memory object to be used by the cpu
         */
        cpu_single_hart(memory &mem) : rv32i_hart(mem) {}

        /**
         * @brief simulates cpu till either halt or hits execution limit
         * @param exec_limit optional instruction execution limit
         */
        void run(uint64_t exec_limit);
};

#endif