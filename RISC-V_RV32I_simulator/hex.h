//******************************************************************
//
// CSCI 463 Assignment 4
//
// Author: Christopher Troyer
// Id: Z1945059
// Course section:  CSCI463-1
//
// Memory Simulator
//
//  I certify that this is my own work and where appropriate an extension 
//  of the starter code provided for the assignment.
//******************************************************************
#ifndef HEX_H
#define HEX_H
#include <string>
/**
 * Utilities for printing formatted hex numbers
 */
class hex
{
    public:
        /**
        * @defgroup to_hexX to hex
        * Format a hex value and return it as a string.
        * @param i Value to be formatted and returned
        * @return std::string
        *@{
        */
        static std::string to_hex8(uint8_t i);  ///Format and return a 8-bit hex value.
        static std::string to_hex32(uint32_t i); ///Format and return a 32-bit hex value.
        static std::string to_hex0x32(uint32_t i); ///Format and return a 32-bit hex value starting with 0x.
        static std::string to_hex0x20(uint32_t i); ///Format and return a 20-bit hex value starting with 0x.
        static std::string to_hex0x12(uint32_t i); ///Format and return a 12-bit hex value starting with 0x.
        /**@}*/

};
#endif