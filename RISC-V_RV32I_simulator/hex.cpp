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
#include <iostream>
#include <sstream>
#include <string>
#include <iomanip>
#include "hex.h"

std::string hex::to_hex8(uint8_t i)
{
    std::ostringstream os;
    os << std::hex << std::setfill('0') << std::setw(2) << static_cast<uint16_t>(i);
    return os.str();
}
std::string hex::to_hex32(uint32_t i)
{
    std::ostringstream os;
    os << std::hex << std::setfill('0') << std::setw(8) << static_cast<uint32_t>(i);
    return os.str();
}
std::string hex::to_hex0x32(uint32_t i)
{
    return std::string("0x")+to_hex32(i);
}

std::string hex::to_hex0x20(uint32_t i)
{    
    std::ostringstream os;
    os << "0x" << std::hex << std::setfill('0') << std::setw(5) << (i);
    return os.str();
}

std::string hex::to_hex0x12(uint32_t i)
{
    std::ostringstream os;
    os << "0x" << std::hex << std::setfill('0') << std::setw(3) << (i);
    return os.str();
}