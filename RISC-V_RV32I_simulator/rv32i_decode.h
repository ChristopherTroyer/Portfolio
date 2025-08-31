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
//  of the starter code (and doxygen video's "handout") provided for the assignment.
//******************************************************************
#ifndef DECODE_H
#define DECODE_H
#include <string>
#include "hex.h"

/**
 * Decodes binary RV32I files and renders them to be human readable
 * 
 */
class rv32i_decode : public hex
{
public:
    /**
     * @brief Checks if passed address is legal
     * 
     * @parm addr The memory address where the insn is stored.
     * @parm insn The instruction.
     * @return a rendered string of the instruction and it's arguments 
     */
    static std::string decode(uint32_t addr, uint32_t insn);

protected:
    static constexpr int mnemonic_width             = 8;

    static constexpr uint32_t opcode_lui            = 0b0110111; 
    static constexpr uint32_t opcode_auipc          = 0b0010111; 
    static constexpr uint32_t opcode_jal            = 0b1101111; 
    static constexpr uint32_t opcode_jalr           = 0b1100111; 
    static constexpr uint32_t opcode_btype          = 0b1100011; 
    static constexpr uint32_t opcode_load_imm       = 0b0000011; 
    static constexpr uint32_t opcode_stype          = 0b0100011; 
    static constexpr uint32_t opcode_alu_imm        = 0b0010011; 
    static constexpr uint32_t opcode_rtype          = 0b0110011; 
    static constexpr uint32_t opcode_system         = 0b1110011; 

    static constexpr uint32_t funct3_beq            = 0b000;
    static constexpr uint32_t funct3_bne            = 0b001;
    static constexpr uint32_t funct3_blt            = 0b100;
    static constexpr uint32_t funct3_bge            = 0b101;
    static constexpr uint32_t funct3_bltu           = 0b110;
    static constexpr uint32_t funct3_bgeu           = 0b111;

    static constexpr uint32_t funct3_lb             = 0b000;
    static constexpr uint32_t funct3_lh             = 0b001;
    static constexpr uint32_t funct3_lw             = 0b010;
    static constexpr uint32_t funct3_lbu            = 0b100;
    static constexpr uint32_t funct3_lhu            = 0b101;

    static constexpr uint32_t funct3_sb             = 0b000; 
    static constexpr uint32_t funct3_sh             = 0b001;
    static constexpr uint32_t funct3_sw             = 0b010; 

    static constexpr uint32_t funct3_add            = 0b000;
    static constexpr uint32_t funct3_sll            = 0b001;
    static constexpr uint32_t funct3_slt            = 0b010;
    static constexpr uint32_t funct3_sltu           = 0b011;
    static constexpr uint32_t funct3_xor            = 0b100;
    static constexpr uint32_t funct3_srx            = 0b101;
    static constexpr uint32_t funct3_or             = 0b110;
    static constexpr uint32_t funct3_and            = 0b111;

    static constexpr uint32_t funct7_srl            = 0b0000000;
    static constexpr uint32_t funct7_sra            = 0b0100000; 

    static constexpr uint32_t funct7_add            = 0b0000000;
    static constexpr uint32_t funct7_sub            = 0b0100000;

    static constexpr uint32_t insn_ecall            = 0x00000073;
    static constexpr uint32_t insn_ebreak           = 0x00100073;

    static constexpr uint32_t funct3_csrrw          = 0b001;
    static constexpr uint32_t funct3_csrrs          = 0b010;
    static constexpr uint32_t funct3_csrrc          = 0b011;
    static constexpr uint32_t funct3_csrrwi         = 0b101;
    static constexpr uint32_t funct3_csrrsi         = 0b110;
    static constexpr uint32_t funct3_csrrci         = 0b111;

    /**
     * @defgroup get_x Get parts from insn
     * Extract specific parts from passed in insn and return them as uint32_t
     * @param insn passed in instruction
     * @return uint32_t seperated part
     * @{
     */
    static uint32_t get_opcode(uint32_t insn); ///Get opcode
    static uint32_t get_rd(uint32_t insn);     ///Get rd
    static uint32_t get_funct3(uint32_t insn); ///Get funct3
    static uint32_t get_rs1(uint32_t insn);    ///Get rs1
    static uint32_t get_rs2(uint32_t insn);    ///Get rs2
    static uint32_t get_funct7(uint32_t insn); ///Get funct7
    static int32_t get_imm_i(uint32_t insn);   ///Get immediate value for an I-type instruction
    static int32_t get_imm_u(uint32_t insn);   ///Get immediate value for a U-type instruction
    static int32_t get_imm_b(uint32_t insn);   ///Get immediate value for a B-type instruction
    static int32_t get_imm_s(uint32_t insn);   ///Get immediate value for a S-type instruction
    static int32_t get_imm_j(uint32_t insn);   ///Get immediate value for a J-type instruction
    /**@}*/

    static constexpr uint32_t XLEN = 32;

    /**
     * @defgroup render_x Renders instruction parts
     * Takes in various parts of instructions to render as human readable strings
     * @param insn passed in instruction
     * @param addr The memory address where the insn is stored.
     * @param mnemonic The specific mnemonic to be rendered.
     * @param imm_i imm_i passed in, for itype_alu
     * @return std::string rendered instruction
     * @{
     */
    static std::string render_illegal_insn(uint32_t insn); ///Default render for unkown or illegal instructions.
    static std::string render_lui(uint32_t insn);          ///Render lui instruction.
    static std::string render_auipc(uint32_t insn);        ///Render auipc instruction.

    static std::string render_jal(uint32_t addr, uint32_t insn);    ///Render jal instruction.
    static std::string render_jalr(uint32_t insn);   ///render jalr instruction.
    static std::string render_btype(uint32_t addr, uint32_t insn, const char *mnemonic); ///Render a btype instruction.

    static std::string render_itype_load(uint32_t insn, const char *mnemonic); ///Render a itype load instruction.
    static std::string render_stype(uint32_t insn, const char *mnemonic); ///Render a stype instruction.
    static std::string render_itype_alu(uint32_t insn, const char *mnemonic, int32_t imm_i); ///Render itype alu instruction.
    static std::string render_rtype(uint32_t insn, const char *mnemonic); ///Render rtype instruction.

    static std::string render_ecall(uint32_t insn);   ///Render ecall instruction.
    static std::string render_ebreak(uint32_t insn);  ///Render ebreak instruction.

    static std::string render_csrrx(uint32_t insn, const char *mnemonic);   ///Render csrrx instruction.
    static std::string render_csrrxi(uint32_t insn, const char *mnemonic);  ///Render csrrxi instruction.

    static std::string render_reg(int r); ///Render register in x0 format
    static std::string render_base_disp(int32_t base, uint32_t disp); ///Render relative address format.
    static std::string render_mnemonic(const std::string &m); ///Render passed in mnemonic.
    /**@}*/
};

#endif