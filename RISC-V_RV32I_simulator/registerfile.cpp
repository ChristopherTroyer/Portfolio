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
#include "registerfile.h"

using std::cerr;
using std::cout;
using std::endl;

registerfile::registerfile()
{
    regs.resize(32);
    reset();
}

void registerfile::reset() //prob finished
{
    //set register 0 to 0
    //redundant since r0 cannot be set using set()
    set(0,0);

    //set the rest to 0xf0f0f0f0
    for (size_t i = 1; i < regs.size(); i++)
    {
        set(i,0xf0f0f0f0);
    }
}

void registerfile::set(uint32_t r, int32_t val) //prob finished
{
    if (r != 0)
    {
        regs[r] = val;
    }
}

int32_t registerfile::get(uint32_t r) const //prob finished
{
    return regs[r];
}

void registerfile::dump(const std::string &hdr) const
{
    for (size_t i = 0; i < regs.size(); i+=8)
    {
        cout << hdr << std::setw(3) << std::setfill(' ') << std::right << 'x' + std::to_string(i)<<" ";

        for (size_t x = i; x < i+8; x++) //Loops to print hex values, prob not 8
        {
            cout<< hex::to_hex32(get(x));
            if ((x-i)==3) //Adds space at midpoint, prob not 7
            {
                cout<<" ";
            }
            if ((x-i)!=7) //prevents extra space at end of line
            {
                cout<<" ";
            }
        }
        cout<<endl;
    }
}