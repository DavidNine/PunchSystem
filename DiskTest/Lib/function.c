#include "common.h"

/**
 * @brief This function is to get current date.
 * 
 * @return return a char* type "time_str".
*/
char* get_Date()
{
    time_t now = time(NULL);
    struct tm *timeinfo = localtime(&now);
    char *time_str;
    time_str = malloc(64);
    memset(time_str, 0x00, sizeof(time_str));
    strftime(time_str, 64, "%Y-%m-%d %H:%M:%S", timeinfo);
    return time_str;
}

/**
 * @brief This function is used to check if the file path is exist
 * 
 * @param path file path
 * 
 * @return If the file exist, return PASS else will return FAIL.
 * 
*/
int chk_file_exist(char *path)
{
    FILE *ptr;
    int ret_val = PASS;

    /** Check Path exist    */
    struct stat buffer;
    int result = stat(path, &buffer);
    if (result == 0)           
    {
        /** the path is exist   */
        if (S_ISDIR(buffer.st_mode))
        {
            /** path is a directory */
            ret_val = FAIL;
        }
        else 
        {
            /** path is a file  */
            ret_val = PASS;
        }
    } 
    else {
        /** File dose not exist*/
        ret_val = FAIL;
    }
    return ret_val;  
}

/**
 * @brief This function is used to check if the disk has any formation
 * 
 * @param disk          disk to be check
*/
int chk_format(char *disk)
{
    char *cmd = malloc(64), *ret = malloc(64);
    sprintf(cmd, "sudo fdisk -l | grep Linux | cut -f1 -d ' ' | grep %s 2>&1 >/dev/null; echo $?", disk);
    
    FILE *ptr;
    ptr = popen(cmd, "r");
    fgets(ret, 64, ptr);

    /** has formation */
    if(strcmp(ret, "0") == 0)
        return FAIL;
    return PASS;
}

/**
 * @brief check disk and get disk, disk will be writen into DiskList.log
 * 
 * @param path      output device path
 * 
 * @param mode      auto, device, run-list mode
 * 
 * @param bypass    bypass mode
*/
int chk_disk(char *path, char *mode, ssize_t bypass)
{
    int chk_ret = PASS , Final_ret = PASS;
    char *cmd = malloc(64), *disk = malloc(64), *diskLogPath = malloc(64), *msg = malloc(1024);
    FILE *ptr, *log_ptr;
    bool has_fmt = false;

    sprintf(msg, "%-30s: %s\n", "Bypass mode", (bypass >= 1 ? "ON" : "OFF"));
    save_color_print(msg,YELLOW);
    sprintf(msg, "%-30s: %s\n\n", "Disk mode", mode);
    save_color_print(msg,YELLOW);
    
    /** open list   */
    sprintf(diskLogPath,"%sDiskList.log", Logfile);
    log_ptr = fopen(diskLogPath, "w");
    if (log_ptr == NULL){
        save_color_print("[ Error ]: Fail to open file: DiskList.log\n", RED);
        return FAIL;
    }
    
    if (strcasecmp(mode, "auto") == 0)          /** auto mode   */
    {


        sprintf(cmd, "sudo fdisk -l | grep dev | cut -f2 -d ' ' | tr -d ':'");
        sprintf(msg, "%-30s: %s\n\n", "Command", cmd);
        save_color_print(msg, YELLOW);

        /** input command and get disk  */
        ptr = popen(cmd, "r");
        while (fgets(disk, 64, ptr))
        {
            /** rm last new line symbol */
            if (disk[strlen(disk)-1] == '\n')
                disk[strlen(disk)-1] = '\0';
            
            chk_ret = chk_format(disk);
            if (chk_ret == FAIL && bypass == 0)
                Final_ret = FAIL;
            
            fprintf(log_ptr, "%-14s %s\n", disk, (chk_ret == PASS? "<Enable>" : "<format>"));
            sprintf(msg, "%-30s: %-14s %s\n", "Get Disk", disk, (chk_ret == PASS? "<Enable>" : "<format>"));
            save_print(msg);
        }
        fclose(ptr);
    }
    else if (strcasecmp(mode, "device") == 0)   /** device mode   */
    {
            chk_ret = chk_format(path);
            if (chk_ret == FAIL && bypass == 0)
                Final_ret = FAIL;
            
            fprintf(log_ptr, "%-14s %s\n", path, (chk_ret == PASS? "<Enable>" : "<format>"));
            sprintf(msg, "%-30s: %-14s %s\n", "Get Disk", path, (chk_ret == PASS? "<Enable>" : "<format>"));
            save_print(msg);
    }
    else                                        /** runlist mode   */
    {
        sprintf(msg, "%-30s: %s\n\n", "List path", path);
        save_color_print(msg, YELLOW);
        ptr = fopen(path, "r");
        if (ptr == NULL){
            save_color_print("[ Error ]: Fail to open list file\n", RED);
            return FAIL;
        }
        while(fgets(disk, 64, ptr))
        {
            if (disk[strlen(disk)-1] == '\n')
                disk[strlen(disk)-1] = '\0';
            
            chk_ret = chk_format(disk);
            if (chk_ret == FAIL && bypass == 0)
                Final_ret = FAIL;

            /** check disk is exist */
            chk_ret = chk_file_exist(disk);
            if (chk_ret != PASS)
            {        
                sprintf(msg, "[ Error ]: Disk:%s does not exist.\n" ,disk);
                save_color_print(msg, RED); 
                Final_ret = ERROR_FILEPATH;
                break;
            }

            fprintf(log_ptr, "%-14s %s\n", disk, (chk_ret == PASS? "<Enable>" : "<format>"));
            sprintf(msg, "%-30s: %-14s %s\n", "Get Disk", disk, (chk_ret == PASS? "<Enable>" : "<format>"));
            save_print(msg);
        }
        fclose(ptr);
    }
    
    
    /** free memory space */
    free(cmd);
    free(disk);
    free(diskLogPath);
    free(msg);
    fclose(log_ptr);
    if (Final_ret == FAIL){
        save_color_print("[ Error ]: Some disk have formation\n", RED);
        return FAIL;
    }
    else if (Final_ret == ERROR_FILEPATH)
    {   
        return FAIL;
    }
    return PASS;
}

/**
 * @brief This function is to init the program setting.
 * 
 * @param argc      number of arguments
 * 
 * @param argv      arguments
*/
int start_process(int argc, char* argv[])
{

    /* initialize */
    FILE *ptr;
    char* msg = malloc(1024);
    
    ptr = fopen(Logpath, "w");
    if (ptr == NULL)
    {
        save_color_print("[ ERROR ]: Fail to open log file.\n", RED);
        return ERROR_INIT;
    }
    fclose(ptr);
    

    /** Formation **\
     * 
     * Date:
     * 
     * Command:
     * 
     * Logpath:
     * 
    \****************/
    

    /* Project name */
    sprintf(msg, "\n%-30s: %s\n\n", "Project", Project);
    save_print(msg);
    
    /* Execute date */
    sprintf(msg, "%-30s: %s\n\n", "Date", get_Date());
    save_print(msg);
    
    /* Log file path */
    sprintf(msg, "%-30s: %s\n\n", "LogPath", Logpath);
    save_print(msg);

    /* Execute command */

    sprintf(msg, "%-30s: %s", "Command", argv[0]);
    for (int i = 1; i < argc; i++)
    {
        sprintf(msg, "%s %s", msg, argv[i]);
    }
    
    sprintf(msg, "%s\n\n", msg);
    save_print(msg);
    
    free(msg);
    return PASS;
}

/**
 * @brief This function is to print whole test program result
 * 
 * @param ret       return value
*/
int end_process(int ret)
{
    
    char *msg = malloc(1024);
    sprintf(msg,"\n%-30s: ", "Final result");
    save_print(msg);
    if (ret == PASS)
        save_color_print("PASS\n",GREEN);
    else 
        save_color_print("FAIL\n",RED);
    sprintf(msg, "%-30s: %s\n", "End time", get_Date());
    save_print(msg);

    free(msg);
}

/**
 * @brief This function will print an format with action word
 * 
 * @param action       words
*/
const void show_action(char *action)
{
    save_print("\n**************************************\n");
    char *msg = malloc(1024);
    sprintf(msg,"@ACT:%s %s\n" , action, "start");
    save_color_print(msg,YELLOW);
    save_print("**************************************\n");
    free(msg);
}

/**
 * @brief This function is used to read Disk in DiskList
 * 
 * @param str           A string line in DiskList
 * 
 * @param path          destnation word, function will put the result to this variable
*/
void extractPath(const char* str, char *path)
{
    const char *start = strchr(str, '/');
    const char *end   = strchr(str, ' ');
    if (start != NULL && end != NULL && end > start)
    {
        memset(path, 0x00, 64);
        strncpy(path, start, end - start);
    }
}

/**
 * @brief This function print current progress of the whole test
 * 
 * @param args          time
*/
void reporter(void* args)
{
    time_t start_time;
    start_time = time(NULL);
    if ((long *)args == 0)
    {
       pthread_exit(NULL);
    }
    long limit_time = (long *)args;
    while(time(NULL) - start_time <= limit_time && !stop_signal ){
        usleep(1000);
        display_disk_status();
    }
    pthread_exit(NULL);
}


long get_size(char* path, char* mode)
{
    /** determine mode  */
    if (strcasecmp(mode, "auto"))
    {
        return PASS;
    }
}

/**
 * Sequent read mode
 * 
 * @param args struct pointer
*/
void sequent_read(void* args)
{   
     struct Attribute{
        char    *disk;
        char    *input;
        ssize_t  pat;
        ssize_t  time;
        ssize_t  skip;
        ssize_t  count;
    };
                    
    
    struct Attribute* attr = (struct Attribute*)args;
    char *read_buffer = malloc(1024), *status = malloc(64), *msg = malloc(64);
    char DiskName[256];
    sprintf(DiskName, "%s", attr->disk);

    pthread_t PID = pthread_self();
    ssize_t read_bytes = 0;
    time_t start_time;

    int disk = open(DiskName, O_RDONLY);
    if (disk == NULL){
        sprintf(msg, "[ Error ]: fail to open disk: %s", DiskName);
        save_color_print(msg, RED);
        return FAIL;
    }

    /** start init */
    start_time = time(NULL);
    sprintf(status,"%.2f%s",(float)(time(NULL) - start_time)/ attr->time *100 , "%" );
    setup_disk(status, DiskName, PID);

    while(time(NULL) - start_time <= attr->time && !stop_signal)
    {
        sprintf(status,"%.2f%s",(float)(time(NULL) - start_time)/(attr->time==0?1:attr->time) *100 , "%");
        disk_status_refresh(status, DiskName, PID);
        while ((read_bytes = read(disk, read_buffer, 1024)) > 0)
        {
            total_reads += read_bytes;
        }
        
        /** Set pointer to start position  */
        lseek(disk, 0, SEEK_SET);
        
        /** run one time if time == 0   */
        if (attr->time == 0)   
        {
            sprintf(status,"done");
            disk_status_refresh(status, DiskName, PID);
            display_disk_status();
            break;
        }   
    }
    sprintf(status,"done");
    disk_status_refresh(status, DiskName, PID);
    display_disk_status();
    free(msg);
    free(status);
    free(read_buffer);
    close(disk);
    /** End of thread   */
    pthread_exit(NULL);
}

void sequent_write(void* args)
{
        
    struct Attribute{
        char    *disk;
        char    *input;
        ssize_t  pat;
        ssize_t  time;
        ssize_t  skip;
        ssize_t  count;
    };
                    
    struct Attribute* attr = (struct Attribute*)args;
    
    char *write_buffer = malloc(1024), *status = malloc(64), *msg = malloc(64), *input_path = malloc(64);
    char DiskName[256];
    sprintf(DiskName, "%s", attr->disk);

    if ((attr->input)[0] != '\0')
    {
        /** Input is a file */
        sprintf(input_path, "%s", attr->input);
    }
    else
    {  
        /** Input is a pattern  */
        memset(write_buffer, attr->pat, 1024);
    }
    

    pthread_t PID = pthread_self();
    ssize_t write_bytes = 0;
    time_t start_time;

    int disk = open(DiskName, O_WRONLY);
    if (disk == NULL){
        sprintf(msg, "[ Error ]: fail to open disk: %s", DiskName);
        save_color_print(msg, RED);
        return FAIL;
    }

    /** start init */
    start_time = time(NULL);
    sprintf(status,"%.2f%s",(float)(time(NULL) - start_time)/ attr->time *100 , "%" );
    setup_disk(status, DiskName, PID);

    while(time(NULL) - start_time <= attr->time && !stop_signal)
    {
        sprintf(status,"%.2f%s",(float)(time(NULL) - start_time)/(attr->time==0?1:attr->time) *100 , "%");
        disk_status_refresh(status, DiskName, PID);

        //===========================================
        //          still needs to be modified
        write_bytes = write(disk, write_buffer, 1024);
        if (write_bytes == -1)
        {
            lseek(disk, 0, SEEK_SET);
            continue;
        }
        /** Set pointer to start position  */
        lseek(disk, 0, SEEK_SET);
        total_writes += write_bytes;
        
        
        //===========================================
        
        /** run one time if time == 0   */
        if (attr->time == 0)   
        {
            sprintf(status,"done");
            disk_status_refresh(status, DiskName, PID);
            display_disk_status();
            break;
        }   
    }

    sprintf(status,"done");
    disk_status_refresh(status, DiskName, PID);
    display_disk_status();

    free(msg);
    free(status);
    free(write_buffer);

    /** End of thread   */
    pthread_exit(NULL);
}
/**
 * Disk Read/Write test function, this func is use to read target data or write data into target.
 * 
 * @param run_list          Disk list, can be 'auto' or a path
 * @param input_device      input device path
 * @param output_device     output device path
 * @param pattern           pattern, an input data type
 * @param mode              test mode, can be 'seq-read', 'seq-write', 'rand-read', and so on
 * @param time              test time, test will run constantly until time up
 * @param skip              adjust start position of input device
 * @param seek              adjust start position of output device
 * @param count             do read/write for 'count' times in a run cycle
*/
int Disk_Read_Write(char *run_list, char *input_device, char *output_device, ssize_t pattern, ssize_t mode, ssize_t time, ssize_t seek, ssize_t count, ssize_t skip)
{
    
    int ret = PASS;

    /** set disk list  */
    char *diskLogPath = malloc(64), *msg = malloc(64), *disk = malloc(64), *line = malloc(64);
    sprintf(diskLogPath,"%sDiskList.log", Logfile);
    memset(disk ,0x00 ,64);
    memset(line ,0x00 ,64);

    /** read disk list  */
    FILE *DiskList_ptr;
    DiskList_ptr = fopen(diskLogPath, "r");

    /** check if the file is open   */
    if(DiskList_ptr == NULL)
    {
        save_color_print("[ Error ]: Cannot open log file.\n", RED);
        ret = FAIL;
        stop_signal = true;
    }
    
    /** pthread arguements  */
    struct Attribute{
        char    *disk;
        char    *input;
        ssize_t  pat;
        ssize_t  time;
        ssize_t  skip;
        ssize_t  count;
    };

    pthread_t thread[20], thread_counter = 0;
    printf("\n\n%-20s%-20s%s\n", "status", "PID", "Disks");
    printf("-------------------------------------------------\n");
    pthread_create(&thread[thread_counter++], NULL, reporter, time);
    while (fgets(line, 64, DiskList_ptr))
    {
     
        if (strstr(line,"<Enable>") != NULL)
        {
            extractPath(line, disk);

            /** get disk    */
            struct Attribute attr;
            attr.pat   = pattern;
            attr.count = count;
            attr.time  = time;
            attr.skip  = skip;
            attr.input  = malloc(64);
            attr.disk  = malloc(64);
            sprintf(attr.input,"%s", input_device);
            sprintf(attr.disk,"%s",disk);
            
            
            switch (mode)
            {

                /** sequent read mode   */
                case seq_read:

                    pthread_create(&thread[thread_counter++], NULL, sequent_read, (void *)&attr);
                    usleep(1000);
                    break;

                /** sequent write mode  */
                case seq_write:
                
                    pthread_create(&thread[thread_counter++], NULL, sequent_write, (void *)&attr);
                    usleep(1000);
                    break;

                case seq_wrc:
                    break;
                case rand_read:
                    
                    
                    break;
                case rand_write:
                    break;
                case rand_wrc:
                    break;
                default:
                    break;

            }
        }
    }

    /** recover source  */
    for (int i=0;i<thread_counter;i++)
    {
        pthread_join(thread[i], NULL);
    }
    
    printf("-------------------------------------------------\n");

    if (stop_signal)
    {
        save_color_print("< --- Press ctrl + C --- > \n", RED); 
        save_color_print("Terminating program...\n\n", RED); 
        ret = FAIL;
    }

    sprintf(msg, "\n%-30s: %ld %s\n", "Total read", total_reads, "Bytes");
    save_color_print(msg,GREEN);
    sprintf(msg, "%-30s: %ld %s\n", "Total write", total_writes, "Bytes");
    save_color_print(msg,GREEN);
    free(disk);
    free(line);
    free(msg);
    free(diskLogPath);
    fclose(DiskList_ptr);
    
    
    return ret;
}

