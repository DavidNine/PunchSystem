#include "common.h"

int disk_counter = 0;
char *disk[20];

void save_print(const char *msg)
{

    FILE *ptr;
    ptr = fopen(Logpath, "a+");
    if (ptr == NULL)
    {
        printf("[ ERROR ]: Fail to open log file.\n");
        return;
    }
    printf(msg);
    fprintf(ptr,msg);
    
    fclose(ptr);
}

void save_color_print(const char *msg, int color)
{

    /*￣￣￣￣￣ color table ￣￣￣￣￣*\
    |   BLACK           '\033[30m'    |
    |   RED             '\033[31m'    |
    |   GREEN           '\033[32m'    |
    |   YELLOW          '\033[33m'    |
    |   BLUE            '\033[34m'    |
    |   MAGENTA         '\033[35m'    |
    |   CYAN            '\033[36m'    |
    |   WHITE           '\033[37m'    |
    \*_______________________________*/


    switch (color)
    {
        case BLACK:
            printf("\033[30m");
            save_print(msg);
            break;
        case RED:
            printf("\033[31m");
            save_print(msg);
            break;
        case GREEN:
            printf("\033[32m");
            save_print(msg);
            break;
        case YELLOW:
            printf("\033[33m");
            save_print(msg);
            break;
        case BLUE:
            printf("\033[34m");
            save_print(msg);
            break;
        case MAGENTA:
            printf("\033[35m");
            save_print(msg);
            break;
        case CYAN:
            printf("\033[36m");
            save_print(msg);
            break;
        case WHITE:
            printf("\033[37m");
            save_print(msg);
            break;
        default:
            printf("[ Error ]: No such coler\n");
            break;
        
    }

    // end of color
    printf("\033[0m");
}

void setup_disk(const char *status, const char *DiskName, long PID)
{
    pthread_mutex_lock(&mutex);
    disk[disk_counter] = malloc(256);
    memset(disk[disk_counter], 0x00, 256);
    sprintf(disk[disk_counter], "%-20s%-20ld%s\n", status, PID, DiskName);
    printf("%s", disk[disk_counter]);
    disk_counter++;
    pthread_mutex_unlock(&mutex);

}

void disk_status_refresh(char status[], char DiskName[], long PID)
{
    pthread_mutex_lock(&mutex);
    char str[20];
    sprintf(str,"%ld", PID);
    for (int i=0;i<disk_counter;i++)
    {
        if (strstr(disk[i], str) != NULL)
        {   
            sprintf(disk[i], "%-20s%-20ld%s\n", status, PID, DiskName);
        }
    }
    pthread_mutex_unlock(&mutex);

}

void display_disk_status()
{
    /** Back to top */
    pthread_mutex_lock(&mutex);

    for (int i=0;i<disk_counter;i++)
    {
        printf("\033[F");
    }
    
    for (int i=0;i<disk_counter;i++)
    {
        printf("%s", disk[i]);
    }
    pthread_mutex_unlock(&mutex);   
}

