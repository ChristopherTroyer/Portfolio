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
#ifndef HART_H
#define HART_H
#include <ostream>
#include "rv32i_decode.h"
#include "memory.h"
#include "registerfile.h"

/**
 * simulation of all possible instructions, inherits from rv32i_decode
 */
class rv32i_hart : public rv32i_decode
{
    public :
        /**
         * @param m memory object passed in 
         */
        rv32i_hart(memory &m) : mem(m) { }
        /**
         * @brief set the bool show_instructions
         * @param b value to set to
         */
        void set_show_instructions( bool b) { show_instructions = b ; }
        /**
         * @brief set the bool show_registers
         * @param b value to set to
         */
        void set_show_registers( bool b) { show_registers = b; }
        /**
         * @return return the bool halt
         */
        bool is_halted() const { return halt; }
        /**
         * @return set the get_halt_reason string
         */
        const std::string &get_halt_reason() const { return halt_reason ; }
        /**
         * @return return the get_insn_counter int
         */
        uint64_t get_insn_counter() const { return insn_counter ; }
        /**
         * @brief set mhartid
          * @param i id value
         */
        void set_mhartid( int i ) { mhartid = i; }

        /**
         * @brief simulate an instruction in the cpu
         * @param hdr the instruction header for any dumps
         */
        void tick( const std::string &hdr ="");
        /**
         * @brief dump state of hart
         * @param hdr the dump header
         */
        void dump( const std::string &hdr ="") const;
        /**
         * @brief completely resets the current hart to its initial state
         */
        void reset ();

    private :
        static constexpr int instruction_width = 35;
        /**
         * @brief decodes instruction then calls the appropriate function to execute it
        *  @param insn the instruction
        *  @param ostream stream to write any output onto
         */
        void exec( uint32_t insn , std::ostream*) ;
        
        /**
         * @brief catch-all for any unkown or invalid instruction
         */
        void exec_illegal_insn( uint32_t insn , std::ostream*) ;

        /**
         * @brief atomic read/write
         */
        void exec_csrrw(uint32_t insn, std::ostream* pos);
        /**
         * @brief atomic read and set
         */
        void exec_csrrs(uint32_t insn, std::ostream* pos);
        /**
         * @brief atomic read and clear
         */
        void exec_csrrc(uint32_t insn, std::ostream* pos);
        /**
         * @brief atomic read and set immediate
         */
        void exec_csrrsi(uint32_t insn, std::ostream* pos);
        /**
         * @brief atomic read and clear immediate
         */
        void exec_csrrci(uint32_t insn, std::ostream* pos);
        /**
         * @brief tomic read/write immediate
         */
        void exec_csrrwi(uint32_t insn, std::ostream* pos);
    //
        /**
         * @brief environment break
         */
        void exec_ebreak(uint32_t insn, std::ostream* pos);
        /**
         * @brief environment call
         */
        void exec_ecall(uint32_t insn, std::ostream* pos);
    //
        /**
         * @brief load upper immediate
         */
        void exec_lui(uint32_t insn, std::ostream* pos);
        /**
         * @brief add upper immediate to pc
         */
        void exec_auipc(uint32_t insn, std::ostream* pos);
        /**
         * @brief jump and link
         */
        void exec_jal(uint32_t insn, std::ostream* pos);
        /**
         * @brief jump and link register
         */
        void exec_jalr(uint32_t insn, std::ostream* pos);

        /**
         * @brief handles branch instructions
         */
        void exec_btype(uint32_t insn, std::ostream* pos);


        /**
         * @brief handles store byte, store half-word, store full-word
         */
        void exec_stype(uint32_t insn, std::ostream* pos);

        /**
         * @brief add
         */
        void exec_add(uint32_t insn, std::ostream* pos);
        /**
         * @brief add immediate
         */
        void exec_addi(uint32_t insn, std::ostream* pos);
        /**
         * @brief subtract
         */
        void exec_sub(uint32_t insn, std::ostream* pos);
        /**
         * @brief shift left logical immediate
         */
        void exec_slli(uint32_t insn, std::ostream* pos);
        /**
         * @brief  set less than immediate
         */
        void exec_slti(uint32_t insn, std::ostream* pos);
        /**
         * @brief  set less than unsigned
         */
        void exec_sltu(uint32_t insn, std::ostream* pos);
        /**
         * @brief  set less than immediate unsigned
         */
        void exec_sltiu(uint32_t insn, std::ostream* pos);

        /**
         * @brief logical exclusive-or immediate
         */
        void exec_xori(uint32_t insn, std::ostream* pos);
        /**
         * @brief logical and immediate
         */
        void exec_andi(uint32_t insn, std::ostream* pos);
        /**
         * @brief logical or immediate
         */
        void exec_ori(uint32_t insn, std::ostream* pos);
        /**
         * @brief shift right arithmatic immediate
         */
        void exec_srai(uint32_t insn, std::ostream* pos);
        /**
         * @brief shift right logical
         */
        void exec_srl(uint32_t insn, std::ostream* pos);
        /**
         * @brief shift right logical immediate
         */
        void exec_srli(uint32_t insn, std::ostream* pos);

        /**
         * @brief logical or
         */
        void exec_or(uint32_t insn, std::ostream* pos);
        /**
         * @brief logical exclusive-or
         */
        void exec_xor(uint32_t insn, std::ostream* pos);
        /**
         * @brief logical and
         */
        void exec_and(uint32_t insn, std::ostream* pos);
        /**
         * @brief set less than
         */
        void exec_slt(uint32_t insn, std::ostream* pos);
        /**
         * @brief shift left logical
         */
        void exec_sll(uint32_t insn, std::ostream* pos);

        /**
         * @brief load byte
         */
        void exec_lb(uint32_t insn, std::ostream* pos);
        /**
         * @brief load byte unsigned
         */
        void exec_lbu(uint32_t insn, std::ostream* pos);
        /**
         * @brief load half word
         */
        void exec_lh(uint32_t insn, std::ostream* pos);
        /**
         * @brief load half word unsigned
         */
        void exec_lhu(uint32_t insn, std::ostream* pos);
        /**
         * @brief load word
         */
        void exec_lw(uint32_t insn, std::ostream* pos);

    //...
        bool halt = { false };
        std::string halt_reason = {"none"};
        bool show_instructions = {false};
        bool show_registers = { false };
    //...
        uint64_t insn_counter = { 0 };
        uint32_t pc = { 0 };
        uint32_t mhartid = { 0 };

    protected :
        registerfile regs; //registers
        memory &mem ;   //memory buffer
};
#endif