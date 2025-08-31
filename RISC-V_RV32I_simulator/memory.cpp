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
#include <vector>
#include <string>
#include <iostream>
#include <fstream>
#include "memory.h"
#include "hex.h"

using std::endl;
using std::cerr;
using std::cout;

memory::memory(uint32_t s)
{
    s = (s+15)&0xfffffff0; //Rounds up to 16
    mem.resize(s,0xa5);    //Resize and fill vector
}

memory::~memory()
{
    mem.clear();          //Delete elements
    mem.shrink_to_fit();  //Frees memory
}

bool memory::check_illegal(uint32_t addr) const 
{
    if (addr >= get_size())
    {
        cout << "WARNING: Address out of range: " << hex::to_hex0x32(addr) << endl;
        int *ptr = NULL; //remove
        *ptr = 1; //remove
    }
    return addr >= get_size();
}

uint32_t memory::get_size() const 
{
    return mem.size();
}

 uint8_t memory::get8(uint32_t addr) const 
 {
    if (!check_illegal(addr))
    {
        return mem[addr];
    }
    else
    {
        return 0;
    }
 }

 uint16_t memory::get16(uint32_t addr) const 
 {
    uint16_t x = (uint16_t) get8(addr);
    uint16_t y = (uint16_t) get8(addr+1);
    return x|y<<8; //return them together in little endian order
 }

uint32_t memory::get32(uint32_t addr) const 
{
    uint32_t x = (uint32_t) get16(addr);
    uint32_t y = (uint32_t) get16(addr+2);
    return x|y<<16; //return them together in little endian order
}

int32_t memory::get8_sx(uint32_t addr) const
{
    int32_t x = get8(addr);
    return x | (x&0x00000080 ? 0xffffff00 : 0); //return as sign extended 32 bit value
}

int32_t memory::get16_sx(uint32_t addr) const
{
    uint16_t x = get16(addr);
    return x | (x&0x00008000 ? 0xffff0000 : 0); //return as sign extended 32 bit value
}

int32_t memory::get32_sx(uint32_t addr) const
{
    return get32(addr); //Already done
}

void memory::set8(uint32_t addr, uint8_t val)
{
    if (!check_illegal(addr))
    {
        mem.at(addr)=val;
    }
}

void memory::set16(uint32_t addr, uint16_t val)
{
    set8(addr+1, val>>8); //stored in little-endian order
    set8(addr, val);
}

void memory::set32(uint32_t addr, uint32_t val)
{
    set16(addr+2, val>>16); //stored in little-endian order
    set16(addr, val);
}

 void  memory::dump() const 
 {
    for (size_t i = 0; i < get_size(); i+=16)
    {
        cout<<to_hex32(i)<<": ";

        for (size_t x = i; x < i+16; x++) //Loops to print hex values
        {
            cout<< to_hex8(get8(x)) << " ";
            if ((x-i)==7) //Adds space at midpoint
            {
                cout<<" ";
            }
        }

        cout<<"*";
        for (size_t x = i; x < i+16; x++) //Loops to print ASCII characters
        {
            uint8_t ch = get8(x);
            ch = isprint(ch) ? ch : '.';
            cout << ch;
        }
        cout<< "*" << endl;
    }
 }

 bool memory::load_file(const std::string &fname)
 {
    bool loadState = true; //Return value, only changes upon failing

    std::ifstream infile(fname, std::ios::in|std::ios::binary); //Loads file in binary mode
    if (!infile.good())
    {
        cerr << "Can't open file " <<fname<< " for reading."<< endl;
        loadState = false;
    }
    else
    {
       uint8_t i;
       infile >> std::noskipws;
       for (uint32_t addr = 0; infile >> i; ++addr)
       {
           if (check_illegal(addr))
           {
                cerr << "Program too big." << endl;
                loadState = false;
                break; //Bad but better than spamming
           } 
           mem[addr] = i;
       }
    }
    //close file
    infile.close();
    return loadState;
 }
