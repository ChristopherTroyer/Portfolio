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
#ifndef REGISTER_H
#define REGISTER_H
#include <string>
#include <vector>
#include <iostream>
#include <iomanip>
#include "hex.h"

using std::cerr;
using std::cout;
using std::endl;

/**
 * CPU register simulator
 */
class registerfile 
{
    private:
        std::vector<int32_t> regs; //Represents registers in cpu
    public:
        registerfile();
        /**
         * @brief Ensures r0 is set to 0 and sets the rest of the registers to 0xf0f0f0f0
         */
        void reset();

        /**
         * @brief sets the value of a register
         * @param r register to set
         * @param val value to set register to
         */
        void set(uint32_t r, int32_t val);
        /**
         * @brief gets a value from a register
         * @param r register to get value from
         * @return value in passed register
         */
        int32_t get(uint32_t r) const;
        /**
         * @brief prints contents of registers with optional header
         * @param string to append to lines of dump
         */
        void dump(const std::string &hdr) const;
};

#endif