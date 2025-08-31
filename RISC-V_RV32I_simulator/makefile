#
#  AUTHOR:  Christopher Troyer
#
CXXFLAGS = -Wall -Werror -std=c++11
CXX = g++

SRCS = $(wildcard *.cpp)
OBJS = $(SRCS:.cpp=.o)

TARGET = RV32I

all: $(TARGET)

$(TARGET): $(OBJS)
	$(CXX) $(CXXFLAGS) -o $@ $^

%.o: %.cpp
	$(CXX) $(CXXFLAGS) -c $<

.PHONY: clean
clean:
	rm -f $(OBJS) $(TARGET)