//*******************************************************************************************************
//*                                                                                                     *
//*     Date: 2023-05-30                                                                                *
//*     Prog: DiskTest.c                                                                                *
//*                                                                                                     *
//*     Desc: This program is used to detect disk read/write.                                           *
//*           Program will test "ALL" disk you want to be tested in the same time.                      *
//*                                                                                                     *
//*     Auth: David Wang.                                                                               *
//*     Dept: GRDC RDC4 RDD3 SDD2                                                                       *
//*                                                                                                     *
//*******************************************************************************************************


/**     Progress    *************************************************************************************
 *
 * --   Argument scanning                           [Finish] -- 2023/06/12
 * 
 * --   Basic program structure                     [Finish] -- 2023/06/13
 * 
 * --   Basic output formation                      [Finish] -- 2023/06/14
 * 
 * --   Sequent read function                       [Finish] -- 2023/06/14
 * 
 * --   Sequent write function                      [Doing]  -- 2023/06/19  note: size check and overflow writing problem still needs to be solved. 
 *
 * --   Sequent read write compare function         [Not yet]
 * 
 * --   Random read function                        [Not yet]
 * 
 * --   Random write function                       [Not yet]
 * 
 * --   Random read write compare function          [Not yet]
 * 
 * 
/**     Progress    *************************************************************************************
*/


//=======================================================================================================
/**
 * Update Record
 * 
 * --   2023/06/14
 * 
 * Desc:
 *      1. Build basic structure and finish sequent read function.
 *      2. Update Usage.
 *          -- time,    adjust the description
 *          -- bypss,   adjust formation
 *      
 * 
 * --   2023/06/19
 * 
 * Desc:
 *      1. Design and implement 'Sequent write function' .
 *      2. Redesign output formation, make it more readable.
 *      3. Redesign function: chk_file_exist().
*/
//=======================================================================================================


#include "common.h"

//============================
//      global variables
//============================

ssize_t args_mode       = seq_read;
ssize_t args_time       = 30;
ssize_t args_seek       = 0;
ssize_t args_skip       = 0;
ssize_t args_count      = 0;
ssize_t args_bypass     = 0;
ssize_t args_pattern    = 0x00;

ssize_t *total_reads     = 0;
ssize_t *total_writes    = 0;

char    *args_if        = NULL;
char    *args_device    = NULL;
char    *args_runlist   = NULL;

bool    auto_mode       = false;
bool    stop_signal     = false;

pthread_mutex_t mutex   = PTHREAD_MUTEX_INITIALIZER;

const char *short_options = "b:i:t:c:p:d:m:";
struct option long_options[] = {
    {"if",          required_argument, 0, 'i'           },
    {"device",      required_argument, 0, 'd'           },
    {"time",        required_argument, 0, 't'           },
    {"count",       required_argument, 0, 'c'           },
    {"mode",        required_argument, 0, 'm'           },
    {"pattern",     required_argument, 0, 'p'           },
    {"bs",          required_argument, 0, ARG_BS        },
    {"ibs",         required_argument, 0, ARG_IBS       },
    {"obs",         required_argument, 0, ARG_OBS       },
    {"seek",        required_argument, 0, ARG_SEEK      },
    {"skip",        required_argument, 0, ARG_SKIP      },
    {"run-list",    required_argument, 0, ARG_RUNLIST   },
    {"report-time", required_argument, 0, ARG_REPORT    },
    {"bypass",      required_argument, 0, 'b'           },
    {"version",     no_argument,    NULL, 'v'           },
    {"help",        no_argument,       0, 'h'           },
    {"detail",      no_argument,       0, ARG_DETAIL    },
    {"mid",         no_argument,       0, ARG_MID       },
    {"seq-read",    no_argument,       0, ARG_SR        },
    {"rand-read",   no_argument,       0, ARG_RR        },
    {"seq-write",   no_argument,       0, ARG_SW        },
    {"rand-write",  no_argument,       0, ARG_RW        },
    {"seq-wrc",     no_argument,       0, ARG_SWR       },
    {"rand-wrc",    no_argument,       0, ARG_RWR       },
    {0, 0, 0, 0}};

void showBanner()
{
    save_print("*****************************************************************************************\n");
    save_print("*                                                                                       *\n");
    save_print("*     Proj: DiskTest                                                                    *\n");
    save_print("*     Date: 2023-05-30                                                                  *\n");
    save_print("*     Desc: This program is used to detect disk read/write.                             *\n");
    save_print("*           Program will test \"ALL\" disk you want to be tested in the same time.        *\n");
    save_print("*                                                                                       *\n");
    save_print("*     Dept: GRDC RDC4 RDD3 SDD2                                                         *\n");
    save_print("*     Auth: David Wang.                                                                 *\n");
    save_print("*                                                                                       *\n");
    save_print("*****************************************************************************************\n");
}

void Usage()
{
    
    save_print("\nUsage: DiskTest [options] <args>\n"                                                                                                      );
    save_print("\n");
    save_print(   " This program is used to read/write and test your disk by using multithread tech.\n"                                                                                );
    save_print("\n");
    save_print(   " options:\n"                                                                                                                             );
    save_print(   "     -i, --if        <filepath>      set input device.\n"                                                                                );
    save_print(   "     -d, --device    <filepath>      set output device.\n"                                                                               );
    save_print(   "     -c, --count     <Num>           set count numbers.\n"                                                                               );
    save_print(   "     -t, --time      <Second>        set runtime, if 0 is given, program will read/write througt whole output device.\n"                            );
    save_print(   "     -b, --bypass    <mode>          set bypass mode.\n"                                                                                 );
    save_print(   "                                     1 | bypass mode on,  if the disk possess formation, program will not abort and pass it. \n"      );
    save_print(   "                                     0 | bypass mode off, if the disk possess formation, program will immediately abort.\n"           );
    save_print(   "     -m, --mode      <mode>          set program read/write mode.\n"                                                                     );
    save_print(   "     --seek          <Num>           set start position of the input device.\n"                                                          );
    save_print(   "     --skip          <Num>           set start position of the output device,\n"                                                         );
    save_print(   "     --run-list      <filepath>      set output device with a device list.\n"                                                            );
    save_print(   "                                     \"auto\" will automatically detect all of your disks.\n"                                            );
    save_print(   "                                     Note:\n"                                                                                            );
    save_print(   "                                         Please use auto mode carefully, Program will overwrite your disk while using writing mode.\n"                       );
    
    
}

void Version()
{
    
    save_print("\nDate    : 2023-05-31\n");
    save_print("Version : v2.00.001\n");

}

int scan_args(int argc, char** argv){

    bool has_if = false , has_device = false , has_time = false , has_count = false , has_mode = false , has_bypass = false
    ,has_bs = false , has_seek = false , has_skip = false , has_runlist = false , has_report = false , has_pattern = false;
    
    char *msg = malloc(64);
    
    if(argc <= 1)
    {   
        sprintf(msg,"%s: No any arguments are given!\n",argv[0]);
        save_print(msg);
        free(msg);
        return ERROR_ARGUMENTS;
    }

    int option, option_index=0;
    while ((option = getopt_long(argc, argv, short_options, long_options, &option_index)) != -1)
    {
        switch (option)
        {
        case 'h':                   //  help
            Usage();
            return EXIT_HELP;
        case 'v':                   //  version
            Version();
            return EXIT_VERSION;
        case 't':                   //  time
            if (has_time)
                return ERROR_DUPLICATE;
            args_time   = atoi(optarg);
            has_time    = true;
            break;
        case 'c':                   //  count
            if (has_count)
                return ERROR_DUPLICATE;
            args_count  = atoi(optarg);
            has_count   = true;
            break;
        case 'b':                   //  bypass
            if (has_bypass)
                return ERROR_DUPLICATE;
            args_bypass = atoi(optarg) >= 1? 1 : 0 ;
            
            has_bypass  = true;
            break;
        case ARG_SEEK:              //  seek
            if (has_seek)
                return ERROR_DUPLICATE;
            args_seek   = atoi(optarg);
            has_seek    = true;
            break;
        case ARG_SKIP:              //  skip
            if (has_skip)
                return ERROR_DUPLICATE;
            args_skip   = atoi(optarg);
            has_skip    = true;
            break;
        case 'd':                   //  output-device
            if (has_device)
                return ERROR_DUPLICATE;
            strcpy(args_device, optarg);
            has_device = true;
            break;
        case 'i':                   //  input-device
            if (has_if)
                return ERROR_DUPLICATE;
            strcpy(args_if, optarg);
            has_if = true;
            break;

        case ARG_RUNLIST:           //  run-list
            if (has_runlist)
                return ERROR_DUPLICATE;
            strcpy(args_runlist,optarg);
            has_runlist = true;
            break;
        case 'p':
            if (has_pattern)
                return ERROR_DUPLICATE;
            char *endptr;
            args_pattern = strtol(optarg,&endptr,16);
            has_pattern = true;
            break;
        case ARG_SR:                //  seq-read
            if (has_mode)
                return ERROR_DUPLICATE;
            args_mode = seq_read;
            has_mode = true;
            break;
        case ARG_RR:                //  rand-read
            if (has_mode)
                return ERROR_DUPLICATE;
            args_mode = rand_read;
            has_mode = true;
            break;
        case ARG_SW:                //  seq-write
            if (has_mode)
                return ERROR_DUPLICATE;
            args_mode = seq_write;
            has_mode = true;
            break;
        case ARG_RW:                //  rand-write
            if (has_mode)
                return ERROR_DUPLICATE;
            args_mode = rand_write;
            has_mode = true;
            break;
        case ARG_SWR:               //  seq-wrc
            if (has_mode)
                return ERROR_DUPLICATE;
            args_mode = seq_wrc;
            has_mode = true;
            break;
        case ARG_RWR:               //  rand-wrc
            if (has_mode)
                return ERROR_DUPLICATE;
            args_mode = rand_wrc;
            has_mode = true;
            break;
        case '?':       //  unknow
            return ERROR_ARGUMENTS;
        default:
            break;
        }
    }

    /** check input and output source   */
    bool WriteMode = false;
    if (args_mode == seq_write || args_mode == seq_wrc || args_mode == rand_write || args_mode == rand_wrc)
        WriteMode = true;
    if (has_if && (has_device == false && has_runlist == false))
        return ERROR_OUTPUTSOURCE;
    if (has_device &&  (has_pattern == false && has_if == false) && WriteMode)
        return ERROR_INPUTSOURCE;
    if (has_runlist && (has_pattern == false && has_if == false) && WriteMode)
        return ERROR_INPUTSOURCE;
    if (has_if && has_pattern)
        return ERROR_DUP_IN;
    if (has_device && has_runlist)
        return ERROR_DUP_OUT;


   /**  check file exist   */
    if (has_if){
        sprintf(msg,"Detecting %-20s%s", "Input device", "..." );
        save_print(msg);
        if (chk_file_exist(args_if) == FAIL){
            save_color_print("FAIL\n", RED);
            return ERROR_FILEPATH;
        }
        save_color_print("OK\n", GREEN);
    }
    if (has_device){
        sprintf(msg,"Detecting %-20s%s", "Output device", "..." );
        save_print(msg);
        if (chk_file_exist(args_device) == FAIL){
            save_color_print("FAIL\n", RED);
            return ERROR_FILEPATH;
        }
        save_color_print("OK\n", GREEN);
    }
    if (has_runlist){   
        if (strcasecmp(args_runlist ,"auto") != 0){
            sprintf(msg,"Detecting %-20s%s", "Output device", "..." );
            save_print(msg);
            if (chk_file_exist(args_runlist) == FAIL){
                save_color_print("FAIL\n", RED);
                return ERROR_FILEPATH; 
            }
            save_color_print("OK\n", GREEN);
        }
        else{
            auto_mode = true;
            sprintf(msg, "%-30s...%s\n", "Auto list mode", "ON");
            save_color_print(msg, YELLOW);
        }
    }
    

    /** check mode  */
    sprintf(msg,"%-30s...", "Detect mode setting");
    save_print(msg);
    if (!has_mode){                  //  set default mode
        save_color_print("NULL\n", RED); 

        sprintf(msg,"%-30s...", "Setting default mode");
        save_color_print(msg, YELLOW);
        args_mode = seq_read;
    }
    save_color_print("OK\n", YELLOW);

    
    show_action("show argments");
    switch (args_mode)
    {
        case seq_read   :
            sprintf(msg,"%-30s: %s\n", "Mode", "sequent read");
            break;
        case seq_write  :
            sprintf(msg,"%-30s: %s\n", "Mode", "sequent write");
            break;
        case rand_read  :
            sprintf(msg,"%-30s: %s\n", "Mode", "random read");
            break;
        case rand_write :
            sprintf(msg,"%-30s: %s\n", "Mode", "random write");
            break;
        case seq_wrc    :
            sprintf(msg,"%-30s: %s\n", "Mode", "sequent write read compare");
            break;
        case rand_wrc   :
            sprintf(msg,"%-30s: %s\n", "Mode", "random write read compare");
            break;
    }


    /** show arguments  */
    save_print(msg);
    if (has_if){
        sprintf(msg,"%-30s: %s\n", "Input device", args_if);
        save_print(msg);
    }
    if (has_device){
        sprintf(msg,"%-30s: %s\n", "Output device", args_device);
        save_print(msg);
    }
    if (has_runlist){
        sprintf(msg,"%-30s: %s\n", "List", args_runlist);
        save_print(msg);
    }
    if (has_pattern){
        sprintf(msg,"%-30s: 0x%02X\n","Pattern",args_pattern);
        save_print(msg);
    }
    sprintf(msg,"%-30s: %ld\n","Time",args_time);
    save_print(msg);
    sprintf(msg,"%-30s: %ld\n","Seek",args_seek);
    save_print(msg);
    sprintf(msg,"%-30s: %ld\n","Skip",args_skip);
    save_print(msg);
    sprintf(msg,"%-30s: %ld\n","Count",args_count);
    save_print(msg);
    sprintf(msg,"%-30s: %ld\n","Bypass",args_bypass);
    save_print(msg);

    free(msg);
    return PASS;

}

int Fail_detect(unsigned int ret){
        
    if (ret == ERROR_INIT)
        save_color_print("[ Error ]: Initialization error.\n", RED);
    else if (ret == EXIT_HELP || ret == EXIT_VERSION){
        end_process(PASS);
        exit(PASS);
    }
    else if (ret == ERROR_ARGUMENTS){
        save_print("Use --help for more information.\n");
    }
    else if (ret == ERROR_DUPLICATE){
        save_color_print("[ Error ]: Duplicated arguemnts are given !\n", RED);
        save_print("Use --help for more information.\n");
    }
    else if (ret == ERROR_FILEPATH){
        save_color_print("[ Error ]: File does not exist.\n", RED);
    }
    else if (ret == ERROR_OUTPUTSOURCE){
        save_color_print("[ Error ]: No output device given.\n", RED);
    }
    else if (ret == ERROR_INPUTSOURCE){
        save_color_print("[ Error ]: No input device given.\n", RED);
    }
    else if (ret == ERROR_DUP_OUT){
        save_color_print("[ Error ]: Cannot have two output device.\n",RED);
    }
    else if (ret == ERROR_DUP_IN)
        save_color_print("[ Error ]: Cannot have two input device.\n",RED);
        
    end_process(FAIL);
    exit(FAIL);
}

int allocate_space(){
    args_if        = malloc(64);
    args_device    = malloc(64);
    args_runlist   = malloc(64);
    memset(args_if, 0x00, sizeof(64));
    memset(args_device, 0x00, sizeof(64));
    memset(args_runlist, 0x00, sizeof(64));
    return PASS;    
}
void handleSIGINT(int signal) {
    stop_signal = true;
}
int main(int argc, char* argv)
{

    unsigned int ret = 0;
    signal(SIGINT, handleSIGINT);
    srand(time(NULL));
    /*  initialization  */
    showBanner();
    allocate_space();
    ret = start_process(argc,argv);
    if (ret != PASS)
        Fail_detect(ret);
    

    /*  scan arguments  */
    show_action("scanning arguments");
    ret = scan_args(argc,argv);
    if (ret != PASS)
        Fail_detect(ret);
    
    /*  checking disk */
    show_action("check disks");
    if (args_device[0] != '\0')
        ret = chk_disk(args_device, "device", args_bypass);
    else if (strcasecmp(args_runlist,"auto") == 0)
        ret = chk_disk(args_runlist, "auto" , args_bypass);
    else
        ret = chk_disk(args_runlist, "run-list", args_bypass);
    if (ret != PASS)
        Fail_detect(ret);

    show_action("read/write disk");
    ret = Disk_Read_Write(args_runlist, args_if, args_device, args_pattern, args_mode, args_time, args_seek, args_count, args_skip);
    if (ret != PASS)
        Fail_detect(ret);

    end_process(PASS);    
}
