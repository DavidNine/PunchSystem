//====================
//      include
//====================

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <stdbool.h>
#include <signal.h>
#include <time.h>
#include <errno.h>
#include <fcntl.h>
#include <unistd.h>
#include <getopt.h>
#include <stdarg.h>
#include <pthread.h>
#include <sys/wait.h>
#include <sys/types.h>
#include <sys/ipc.h>
#include <sys/shm.h>
#include <sys/stat.h>
#include <ctype.h>

#define BLACK       66  // Ascii   'B'
#define RED         67  // Ascii   'C'
#define GREEN       68  // Ascii   'D'
#define YELLOW      69  // Ascii   'E'
#define BLUE        70  // Ascii   'F'
#define MAGENTA     71  // Ascii   'G'
#define CYAN        72  // Ascii   'H'
#define WHITE       73  // Ascii   'I'


#define ARG_IF      65  // ASCII 'A'
#define ARG_OF      66  // ASCII 'B'
#define ARG_BS      67  // ASCII 'C'
#define ARG_IBS     68  // ASCII 'D'
#define ARG_OBS     69  // ASCII 'E'
#define ARG_SEEK    70  // ASCII 'F'
#define ARG_SKIP    71  // ASCII 'G'
#define ARG_TIME    72  // ASCII 'H'
#define ARG_COUNT   73  // ASCII 'I'
#define ARG_RUNLIST 77  // ASCII 'M'
#define ARG_DETAIL  78  // ASCII 'N'
#define ARG_MID     79  // ASCII 'O'
#define ARG_REPORT  80  // ASCII 'P'
#define ARG_SR      81  // ASCII 'Q' 
#define ARG_RR      82  // ASCII 'R'
#define ARG_SW      83  // ASCII 'S'
#define ARG_RW      84  // ASCII 'T'
#define ARG_SWR     85  // ASCII 'U'
#define ARG_RWR     86  // ASCII 'V'




//===========================
//      Return define
//===========================
#define PASS 0
#define FAIL 1

#define EXIT_HELP           0x0002
#define EXIT_VERSION        0x0004
#define ERROR_DUPLICATE     0x0008
#define ERROR_INIT          0x1000
#define ERROR_FILEPATH      0x0010
#define ERROR_INPUTSOURCE   0x0020
#define ERROR_OUTPUTSOURCE  0x0040
#define ERROR_DUP_OUT       0x0080
#define ERROR_DUP_IN        0x0100
#define ERROR_ARGUMENTS     0xffff

//===========================
//      Mode define
//===========================

#define seq_read            0x0001
#define rand_read           0x0002
#define seq_write           0x0003
#define rand_write          0x0004
#define seq_wrc             0x0005
#define rand_wrc            0x0006



#define Project "DiskTest"
#define Logfile "/home/ninebro/workspace/DiskTest/Log/"
#define Logpath "/home/ninebro/workspace/DiskTest/Log/DiskTest.log"



extern bool stop_signal;
extern ssize_t *total_reads;
extern ssize_t *total_writes;
extern pthread_mutex_t mutex;

//==========================
//      from function.c
//==========================

int chk_file_exist(char *path);
int chk_disk(char *path, char *mode, ssize_t bypass);
int start_process(int argc, char* argv[]);
int end_process(int ret);
int Disk_Read_Write(char *run_list, char *input_device, char *output_device, ssize_t pattern, ssize_t mode, ssize_t time, ssize_t seek, ssize_t count, ssize_t skip);

//==========================
//      from message.c
//==========================

void save_print(const char *msg);
void save_color_print(const char *msg, int color);
const void show_action(char *action);
void setup_disk(const char *status, const char *DiskName, long PID);
void disk_status_refresh(char status[], char DiskName[], long PID);
void display_disk_status();
void printAll();
