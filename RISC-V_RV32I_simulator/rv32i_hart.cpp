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
#include "rv32i_hart.h"
#include "rv32i_decode.h"
#include <iomanip> 
#include <cassert>

void rv32i_hart::tick (const std::string &hdr)
{
    if (show_registers)
    {
        //dump state of hart with HDR
        dump(hdr);
    }

    if (pc % 4 != 0)
    {
        halt = true;
        halt_reason = "PC alignment error";
    }
    else
    {
        insn_counter++;
        //fetch an instruction from the memory at the address in pc register

        uint32_t insn = mem.get32(pc);

        //if show_instructions is true then
        // print hdr, pc register, and 32 bit fetched instruction in hex
        // call exec() to execute instruction and render it and sim details
        if (show_instructions)
        {
            std::cout << hdr << to_hex32(pc) << ": "<<  to_hex32(insn) << "  ";
            exec(insn, &std::cout);
            std::cout << std::endl;
        }
        else //else call exec() with a null pointer to execute without rendering
        {
            exec(insn, nullptr);
        }
    }
}

void rv32i_hart::dump (const std::string &hdr) const // prob done
{
    regs.dump(hdr);
    std::cout << hdr << " pc " << to_hex32(pc) << std::endl;
}

void rv32i_hart::reset ()
{
    pc = 0;
    regs.reset();
    insn_counter = 0;
    halt = false;
    halt_reason = "none";
}

void rv32i_hart::exec ( uint32_t insn, std::ostream *pos)
{
    uint32_t opcode = get_opcode(insn);
    uint32_t funct3 = get_funct3(insn);
    uint32_t funct7 = get_funct7(insn);

    //...
    switch (opcode)
    {
    default:             exec_illegal_insn(insn, pos); return;
    case opcode_system:  
        switch (funct3)
        {
            default:              exec_illegal_insn(insn, pos); return;
            case funct3_csrrw:    exec_csrrw(insn, pos); return;
            case funct3_csrrs:    exec_csrrs(insn, pos); return;
            case funct3_csrrc:    exec_csrrc(insn, pos); return;
            case funct3_csrrwi:   exec_csrrwi(insn, pos); return;
            case funct3_csrrsi:   exec_csrrsi(insn, pos); return;
            case funct3_csrrci:   exec_csrrci(insn, pos); return;
            case funct3_beq:
                switch (insn)
                {                
                    default:    exec_illegal_insn(insn, pos); return;
                    case insn_ebreak: return exec_ebreak(insn, pos);
                    case insn_ecall:  return exec_ecall(insn, pos);
                }
        assert(0 && "unrecognized imm_i");
        
        //------------------------------
        }
        assert(0 && "unrecognized funct3");
        case opcode_lui:     exec_lui(insn, pos); return;
        case opcode_auipc:   exec_auipc(insn, pos); return;
        case opcode_jal:     exec_jal(insn, pos); return;
        case opcode_jalr:    exec_jalr(insn, pos); return;
        case opcode_btype:   exec_btype(insn, pos); return;
        
        case opcode_stype:   exec_stype(insn, pos); return;

        case opcode_load_imm:
        switch (funct3)
        {
        default:    exec_illegal_insn(insn, pos); return;
            case funct3_lb:     exec_lb(insn, pos); return;
            case funct3_lh:     exec_lh(insn, pos); return;
            case funct3_lw:     exec_lw(insn, pos); return;
            case funct3_lbu:    exec_lbu(insn, pos); return;
            case funct3_lhu:    exec_lhu(insn, pos); return;
            assert(0 && "unrecognized funct3"); 
        }
        case opcode_alu_imm:
            switch (funct3)
            {
                default:    exec_illegal_insn(insn, pos); return;
                case funct3_add: exec_addi(insn, pos); return;
                case funct3_sll: exec_slli(insn, pos); return;
                case funct3_slt: exec_slti(insn, pos); return;
                case funct3_sltu:exec_sltiu(insn, pos); return;
                case funct3_xor: exec_xori(insn, pos); return;
                case funct3_and: exec_andi(insn, pos); return;
                case funct3_or:  exec_ori(insn, pos); return;
                case funct3_srx:
                    switch (funct7)
                    {
                        default:    exec_illegal_insn(insn, pos); return;
                        case funct7_sra: exec_srli(insn, pos); return;
                        case funct7_srl: exec_srli(insn, pos); return;
                    }
                    assert(0 && "unrecognized funct7");
            }
            assert(0 && "unrecognized funct3");
        case opcode_rtype:
            switch (funct3)
            {
                default:    exec_illegal_insn(insn, pos); return;
                case funct3_add:
                    switch (funct7)
                    {
                        default:    exec_illegal_insn(insn, pos); return;
                        case funct7_add: exec_add(insn, pos); return;
                        case funct7_sub: exec_sub(insn, pos); return;
                    }
                    assert(0 && "unrecognized funct7");
                case funct3_srx:
                    switch (funct7)
                    {
                        default:         exec_illegal_insn(insn, pos); return;
                        case funct7_sra: exec_srl(insn, pos); return;
                        case funct7_srl: exec_srl(insn, pos); return;
                    }
                case funct3_or: exec_or(insn, pos); return;
                case funct3_xor: exec_xor(insn, pos); return;
                case funct3_and: exec_and(insn,pos); return;
                case funct3_slt: exec_slt(insn, pos); return;
                case funct3_sll: exec_sll(insn, pos); return;
                case funct3_sltu: exec_sltu(insn, pos); return;
            }
    }
}

void rv32i_hart::exec_illegal_insn ( uint32_t insn, std::ostream* pos)
{
    if (pos)
        *pos << render_illegal_insn(insn);
    halt = true;
    halt_reason = "Illegal instruction";
}

void rv32i_hart::exec_add(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t rs2 = get_rs2(insn);

    int32_t val = (regs.get(rs1) + regs.get(rs2));

    if (pos)
    {
        std::string s =  render_rtype(insn, "add");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " + " << hex::to_hex0x32(regs.get(rs2)) << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_addi(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t imm_i = get_imm_i(insn);

    int32_t val = (regs.get(rs1) + imm_i);

    if (pos)
    {
        std::string s = render_itype_alu(insn, "addi", imm_i);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " + " <<hex::to_hex0x32(imm_i) << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_and(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t rs2 = get_rs2(insn);

    int32_t val = (regs.get(rs1) & regs.get(rs2));

    if (pos)
    {
        std::string s = render_rtype(insn, "and");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " & " << hex::to_hex0x32(regs.get(rs2)) << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_andi(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t imm_i = get_imm_i(insn);

    int32_t val = (regs.get(rs1) & imm_i);

    if (pos)
    {
        std::string s = render_itype_alu(insn, "andi", imm_i);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " & " << hex::to_hex0x32(imm_i) << " = " << hex::to_hex0x32(val);
    }
    
    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_auipc(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t imm_u = get_imm_u(insn);

    int32_t val = (pc + imm_u);

    if (pos)
    {
        std::string s = render_auipc(insn);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(pc) << " + " <<hex::to_hex0x32(imm_u) << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

// ----------------------------------------------------------------
// B stuff

void rv32i_hart::exec_btype(uint32_t insn, std::ostream* pos)
{
    uint32_t funct3 = get_funct3(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t rs2 = get_rs2(insn);
    uint32_t imm_b = get_imm_b(insn);
    std::string render_str = "";
    std::string mnemonic = "";
    switch (funct3)
    {
        case funct3_beq: 
            if (pos)
            {
                render_str = " == ";
                mnemonic = render_btype(pc, insn, "beq");
            }
            pc = pc + ((regs.get(rs1) == regs.get(rs2)) ? imm_b : 4);
            break;
        case funct3_bge: 
            if (pos)
            {
                render_str = " >= ";
                mnemonic = render_btype(pc, insn, "bge");
            }
            pc = pc + ((regs.get(rs1) >= regs.get(rs2)) ? imm_b : 4);
            break;
        case funct3_bgeu: 
            if (pos)
            {
                render_str = " >=U ";
                mnemonic = render_btype(pc, insn, "bgeu");
            }
            pc = pc + (((unsigned)regs.get(rs1) >= (unsigned)regs.get(rs2)) ? imm_b : 4);
            break;
        case funct3_blt: 
            if (pos)
            {
                render_str = " < ";
                mnemonic = render_btype(pc, insn, "blt");
            }
            pc = pc + ((regs.get(rs1) < regs.get(rs2)) ? imm_b : 4);
            break;
        case funct3_bltu: 
            if (pos)
            {
                render_str = " <U ";
                mnemonic = render_btype(pc, insn, "bltu");
            }
            pc = pc + (((unsigned)regs.get(rs1) < (unsigned)regs.get(rs2)) ? imm_b : 4);
            break;
        case funct3_bne: 
            if (pos)
            {
                render_str = " != ";
                mnemonic = render_btype(pc, insn, "bne");
            }
            pc = pc + ((regs.get(rs1) != regs.get(rs2)) ? imm_b : 4);
            break;
    }

    if (pos)
    {
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << mnemonic;
        *pos << "// pc += (" << hex::to_hex0x32(regs.get(rs1)) << render_str << hex::to_hex0x32(regs.get(rs2)) << " ? " << hex::to_hex0x32(imm_b) << " : 4) = " << hex::to_hex0x32(pc);
    }

}

//----------------------------------------------------------------
// c stuff

void rv32i_hart::exec_csrrw(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t csr = get_imm_u(insn);

    if (pos)
    {
        std::string s = render_csrrx(insn, "csrrw");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
    }

    csr = regs.get(rs1);
    regs.set(rd, csr);

    pc += 4;
}

void rv32i_hart::exec_csrrs(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t csr = 0;

    csr = csr|regs.get(rs1);

    if (pos)
    {
        std::string s = render_csrrx(insn, "csrrs");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << csr;
    }

    regs.set(rd, csr);

    pc += 4;
}

void rv32i_hart::exec_csrrc(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t csr = get_imm_u(insn);

    if (pos)
    {
        std::string s = render_csrrx(insn, "csrrc");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
    }

    csr = regs.get(rs1);
    regs.set(rd, csr);

    pc += 4;
}

void rv32i_hart::exec_csrrwi(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t csr = get_imm_u(insn);

    if (pos)
    {
        std::string s = render_csrrxi(insn, "csrrwi");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
    }

    csr = regs.get(rs1);
    regs.set(rd, csr);

    pc += 4;
}

void rv32i_hart::exec_csrrsi(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t csr = get_imm_u(insn);

    if (pos)
    {
        std::string s = render_csrrxi(insn, "csrrsi");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
    }

    csr = regs.get(rs1);
    regs.set(rd, csr);

    pc += 4;
}

void rv32i_hart::exec_csrrci(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t csr = get_imm_u(insn);

    if (pos)
    {
        std::string s = render_csrrxi(insn, "csrrci");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
    }

    csr = regs.get(rs1);
    regs.set(rd, csr);

    pc += 4;
}
//----------------------------------------------------------------

void rv32i_hart::exec_ecall(uint32_t insn, std::ostream* pos)
{
    if (pos)
    {
        std::string s = render_ecall(insn);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// TRANSFER CONTROL TO DEBUGGER";
    }
}

void rv32i_hart::exec_ebreak(uint32_t insn, std::ostream* pos)
{
    if (pos)
    {
        std::string s = render_ebreak(insn);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// HALT";
    }
    halt = true;
    halt_reason ="EBREAK instruction";
}

//----------------------------------------------------------------
// J stuff
void rv32i_hart::exec_jal(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t imm_j = get_imm_j(insn);
    int32_t val = (pc+4);
    regs.set(rd, val);
    if (pos)
    {
        std::string s = render_jal(pc, insn);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(val) << ",  pc = ";
        *pos << hex::to_hex0x32(pc)  << " + " << hex::to_hex0x32(imm_j)  <<  " = " << hex::to_hex0x32(pc+imm_j);
    }

    pc += imm_j;

}
void rv32i_hart::exec_jalr(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t imm_i = get_imm_i(insn);

    int32_t val = (pc+4);
    pc = ((regs.get(rs1) + imm_i)&~1);

    if (pos)
    {
        std::string s = render_jalr(insn);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(val) << ",  pc = (" << hex::to_hex0x32(imm_i) << " + " << hex::to_hex0x32(regs.get(rs1)) <<  ") & " << hex::to_hex0x32(0xfffffffe) <<" = " << hex::to_hex0x32(pc);
    }

    regs.set(rd, val);
}

//----------------------------------------------------------------
// L stuff

void rv32i_hart::exec_lb(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t imm_i = get_imm_i(insn);

    int8_t val = (mem.get8_sx(regs.get(rs1)+imm_i));

    if (pos)
    {
        std::string s = render_itype_load(insn, "lb");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = sx(m8(" << hex::to_hex0x32(regs.get(rs1)) << " + " <<hex::to_hex0x32(imm_i) << ")) = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4; 
}

void rv32i_hart::exec_lbu(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t imm_i = get_imm_i(insn);

    uint8_t val = (mem.get8(regs.get(rs1)+imm_i));

    if (pos)
    {
        std::string s = render_itype_load(insn, "lbu");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = zx(m8(" << hex::to_hex0x32(regs.get(rs1)) << " + " <<hex::to_hex0x32(imm_i) << ")) = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4; 
}

void rv32i_hart::exec_lh(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t imm_i = get_imm_i(insn);

    int16_t val = (mem.get16_sx(regs.get(rs1)+imm_i));

    if (pos)
    {
        std::string s = render_itype_load(insn, "lh");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = sx(m16(" << hex::to_hex0x32(regs.get(rs1)) << " + " <<hex::to_hex0x32(imm_i) << ")) = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_lhu(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t imm_i = get_imm_i(insn);

    uint16_t val = (mem.get16(regs.get(rs1)+imm_i));

    if (pos)
    {
        std::string s = render_itype_load(insn, "lhu");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = zx(m16(" << hex::to_hex0x32(regs.get(rs1)) << " + " <<hex::to_hex0x32(imm_i) << ")) = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4; 
}

void rv32i_hart::exec_lui(uint32_t insn, std::ostream* pos)
{
    int32_t rd = get_rd(insn);
    int32_t imm_u = get_imm_u(insn);

    if (pos)
    {
        std::string s = render_lui(insn);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(imm_u);
    }

    regs.set(rd, imm_u);
    pc += 4;
}

void rv32i_hart::exec_lw(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t imm_i = get_imm_i(insn);

    int32_t val = (mem.get32(regs.get(rs1)+imm_i));;

    if (pos)
    {
        std::string s = render_itype_load(insn, "lw");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = sx(m32(" << hex::to_hex0x32(regs.get(rs1)) << " + " <<hex::to_hex0x32(imm_i) << ")) = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;

}

//----------------------------------------------------------------

void rv32i_hart::exec_or(uint32_t insn, std::ostream* pos)
{
    int32_t rd = get_rd(insn);
    int32_t rs1 = get_rs1(insn);
    int32_t rs2 = get_rs2(insn);
    
    int32_t val = (regs.get(rs1) | (regs.get(rs2)));

    if (pos)
    {
        std::string s = render_rtype(insn, "or");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " | " << hex::to_hex0x32(regs.get(rs2)) << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_ori(uint32_t insn, std::ostream* pos)
{
    int32_t rd = get_rd(insn);
    int32_t rs1 = get_rs1(insn);
    int32_t imm_i = get_imm_i(insn);
    
    int32_t val = (regs.get(rs1) | imm_i);

    if (pos)
    {
        std::string s = render_itype_alu(insn, "ori", imm_i);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " | " << hex::to_hex0x32(imm_i) << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}
//----------------------------------------------------------------

void rv32i_hart::exec_stype(uint32_t insn, std::ostream* pos)
{
    uint32_t funct3 = get_funct3(insn);
    int32_t rs1 = get_rs1(insn);
    int32_t rs2 = get_rs2(insn);
    int32_t imm_s = get_imm_s(insn);

    std::string s = "";
    std::string pf = "";

    int32_t sum = (regs.get(rs1)+imm_s);

    switch (funct3)
    {
        case funct3_sb: mem.set8((sum), regs.get(rs2)&0xff); s = render_stype(insn, "sb"); pf="m8"; break;
        case funct3_sh: mem.set16((sum), regs.get(rs2)&0xffff); s = render_stype(insn, "sh"); pf="m16"; break;
        case funct3_sw: mem.set32((sum), regs.get(rs2)&0xffffffff); s = render_stype(insn, "sw"); pf="m32"; break;
    }

    if (pos)
    {
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << pf << "(" << hex::to_hex0x32(regs.get(rs1)) << " + " << hex::to_hex0x32(imm_s) << ") = ";
        switch (funct3)
        {
            case funct3_sb: *pos << hex::to_hex0x32(mem.get8(sum)); break;
            case funct3_sh: *pos << hex::to_hex0x32(mem.get16(sum)); break;
            case funct3_sw: *pos << hex::to_hex0x32(mem.get32(sum)); break;
        }
    }
    pc += 4;
}

void rv32i_hart::exec_sll(uint32_t insn, std::ostream* pos) 
{
    int32_t rd = get_rd(insn);
    int32_t rs1 = get_rs1(insn);
    int32_t rs2 = get_rs2(insn);
    
    int32_t val = (regs.get(rs1) <<  (regs.get(rs2)%XLEN));

    if (pos)
    {
        std::string s = render_rtype(insn,"sll");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " << " << (regs.get(rs2)%XLEN) << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_slli(uint32_t insn, std::ostream* pos)
{
    int32_t rd = get_rd(insn);
    int32_t rs1 = get_rs1(insn);
    int32_t shamt_i = get_rs2(insn);

    
    int32_t val = (regs.get(rs1) << shamt_i);

    if (pos)
    {
        std::string s = render_itype_alu(insn, "slli", shamt_i%XLEN);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " << " << std::dec << shamt_i << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
    
}

void rv32i_hart::exec_slt(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t rs2 = get_rs2(insn);

    int32_t val = (regs.get(rs1) < regs.get(rs2)) ? 1 : 0;

    if (pos)
    {
        std::string s = render_rtype(insn, "slt   ");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = (" << hex::to_hex0x32(regs.get(rs1)) << " < " << hex::to_hex0x32(regs.get(rs2)) << ") ? 1 : 0 = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_slti(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    int32_t imm_i = get_imm_i(insn);

    int32_t val = (regs.get(rs1) < imm_i) ? 1 : 0;

    if (pos)
    {
        std::string s = render_itype_alu(insn,"slti", imm_i);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = (" << hex::to_hex0x32(regs.get(rs1)) << " < "  << std::dec << (imm_i) << ") ? 1 : 0 = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_sltiu(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t imm_i = get_imm_i(insn);

    uint32_t val = ((unsigned)regs.get(rs1) < (unsigned)imm_i) ? 1 : 0;

    if (pos)
    {
        std::string s = render_itype_alu(insn,"sltiu", imm_i);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = (" << hex::to_hex0x32(regs.get(rs1)) << " <U " << (imm_i) << ") ? 1 : 0 = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_sltu(uint32_t insn, std::ostream* pos) 
{
        uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t rs2 = get_rs2(insn);

    int32_t val = (regs.get(rs1) < regs.get(rs2)) ? 1 : 0;

    if (pos)
    {
        std::string s = render_rtype(insn, "sltu   ");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = (" << hex::to_hex0x32(regs.get(rs1)) << " <U " << hex::to_hex0x32(regs.get(rs2)) << ") ? 1 : 0 = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_srl(uint32_t insn, std::ostream* pos)
{
    int32_t rd = get_rd(insn);
    int32_t rs1 = get_rs1(insn);
    int32_t rs2 = get_rs2(insn);
    int32_t funct7 = get_funct7(insn);
    
    int32_t val = 0;

    switch (funct7)
    {
        case funct7_sra: val = (regs.get(rs1) >> (regs.get(rs2)%XLEN)); break;
        case funct7_srl: val = ((unsigned int32_t)regs.get(rs1) >> (regs.get(rs2)%XLEN)); break;
    }

    if (pos)
    {
        std::string s = "";
        switch (funct7)
        {
            case funct7_sra: s = render_rtype(insn, "sra"); break;
            case funct7_srl: s = render_rtype(insn, "srl"); break;
        }
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " >> " << std::dec << regs.get(rs2)%XLEN << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_srli(uint32_t insn, std::ostream* pos)
{
    int32_t rd = get_rd(insn);
    int32_t rs1 = get_rs1(insn);
    int32_t shamt_i = get_rs2(insn);
    int32_t funct7 = get_funct7(insn);
    
    int32_t val = 0;

    switch (funct7)
        {
            case funct7_sra: val = (regs.get(rs1) >> shamt_i); break;
            case funct7_srl: val = ((unsigned int32_t)(regs.get(rs1)) >> shamt_i); break;
        }

    if (pos)
    {
        std::string s = "";
        switch (funct7)
        {
            case funct7_sra: s = render_itype_alu(insn, "srai", shamt_i%XLEN); break;
            case funct7_srl: s = render_itype_alu(insn, "srli", shamt_i%XLEN); break;
        }
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " >> " << std::dec << shamt_i << " = " << hex::to_hex0x32(val);
        
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_sub(uint32_t insn, std::ostream* pos)
{
    uint32_t rd = get_rd(insn);
    uint32_t rs1 = get_rs1(insn);
    uint32_t rs2 = get_rs2(insn);

    int32_t val = (regs.get(rs1) - regs.get(rs2));

    if (pos)
    {
        std::string s = render_rtype(insn, "sub");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
                *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " - " << hex::to_hex0x32(regs.get(rs2)) << " = " << hex::to_hex0x32(val);
        
    }

    regs.set(rd, val);
    pc += 4;
}
//----------------------------------------------------------------
void rv32i_hart::exec_xor(uint32_t insn, std::ostream* pos)
{
    int32_t rd = get_rd(insn);
    int32_t rs1 = get_rs1(insn);
    int32_t rs2 = get_rs2(insn);
    
    int32_t val = (regs.get(rs1) ^ (regs.get(rs2)));

    if (pos)
    {
        std::string s = render_rtype(insn, "xor");
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " ^ " << hex::to_hex0x32(regs.get(rs2)) << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}

void rv32i_hart::exec_xori(uint32_t insn, std::ostream* pos)
{
    int32_t rd = get_rd(insn);
    int32_t rs1 = get_rs1(insn);
    int32_t imm_i = get_imm_i(insn);
    
    int32_t val = (regs.get(rs1) ^ imm_i);

    if (pos)
    {
        std::string s = render_itype_alu(insn, "xori", imm_i);
        *pos << std::setw(instruction_width) << std::setfill(' ') << std::left << s;
        *pos << "// " << render_reg(rd) << " = " << hex::to_hex0x32(regs.get(rs1)) << " ^ " << hex::to_hex0x32(imm_i) << " = " << hex::to_hex0x32(val);
    }

    regs.set(rd, val);
    pc += 4;
}
