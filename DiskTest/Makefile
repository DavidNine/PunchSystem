# ----------------------- Project -------------------------------------------
PROJECT     = DiskTest
# ---------------------------------------------------------------------------
# MARCO DEFINE
#OS_TYPE        = _WIN32
BUILD_DATE      = __BUILD_DATE__=\"`date +%Y/%m/%d`\"
BUILD_TOOL      = GCC_BUILD
#USERDEFINES    = __LICENSE_CHECK__ 
SYSDEFINES      = ${OS_TYPE} ${BUILD_TOOL}
# FILE PATH
INCLUDEPATH     = Header
LIBRARY_PATH    = Lib
OBJECT_PATH     = Obj
OUTPUTPATH      = MAKEDIR
# LIB DEFINE
LIBPATH         = 
LIBFILES        = 
TOOLSPATH       = 
# ---------------------------------------------------------------------------
CC          = gcc
LINKER      = gcc
LIBER       =
# ---------------------------------------------------------------------------
CFLAGS      = -c -w -Wall
LFLAGS      = -s 
# ---------------------------------------------------------------------------
ALLDEFINES  = $(addprefix -D, $(USERDEFINES)) $(addprefix -D, $(SYSDEFINES)) -D$(BUILD_DATE)
ALLINCLUDES = $(addprefix -I, $(INCLUDEPATH))
ALLLIBPATH  = $(addprefix -L, $(LIBPATH))
ALLLIBS	    = $(addprefix -l, $(LIBFILES))
ALLSOBJ     = $(wildcard $(OBJECT_PATH)/*.o)
ALLOBJ      = $(patsubst $(LIBRARY_PATH)/%.c,$(OUTPUTPATH)/%.o,$(wildcard $(LIBRARY_PATH)/*.c))
# ---------------------------------------------------------------------------
all:clean $(OUTPUTPATH) $(PROJECT)
	
$(PROJECT):$(ALLOBJ) ${OUTPUTPATH}/${PROJECT}.o
	$(LINKER) $(LFLAGS) -o $(PROJECT) ${OUTPUTPATH}/${PROJECT}.o $(ALLOBJ) $(ALLSOBJ) $(ALLLIBPATH) $(ALLLIBS) 

clean:
	rm -f $(PROJECT)
	rm -rf $(OUTPUTPATH)
# ---------------------------------------------------------------------------
$(OUTPUTPATH):
	mkdir $(OUTPUTPATH)

	# Project common
${OUTPUTPATH}/${PROJECT}.o: ${PROJECT}.c
	$(CC) $(CFLAGS) $(ALLDEFINES) $(ALLINCLUDES) -o $@ $<

${OUTPUTPATH}/%.o: ${LIBRARY_PATH}/%.c
	$(CC) $(CFLAGS) $(ALLDEFINES) $(ALLINCLUDES) -o $@ $<

# ---------------------------------------------------------------------------
