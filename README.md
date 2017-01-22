#phpGlluchEdX

phpGlluchEdX is a collection of scripts to obtain 
the metadata from a group of [EdX](https://www.edx.org/) courses. 

Tested in novembre 2015

 ## Requisites
 
 Freeling as server. Change normalize.php and put your IP and port.ç
 
  ### Get all courses
  
  Rip all the site, for example with [HTTrak](http://www.httrack.com/).
 
 ### Courses
 
 Go to the ripped site, to _www.edx.org/course_ 
 and copy all of them to courses dir in this project.
 
 
 ## Execution order
 
 This files has to be executed in php CLI in this order:
 
 1. **php description.php**. Retrieves information from edX courses.    
 2. **php pre_clean.php**. Deletes some irrelevant texts. 
 3. **php lang_ordering.php**. Put the courses in a dir with the lang as name.
 Results are in json0/en/  or json0/es/
 4. **php normalize.php**. Adds POS tagging information.
 5. **php clean.php**. Remove puntuation from POS tagging.
 
 ## Result
 The courses information will be in the directory *json2*,
  for POS tagged results or in _json0/en/_ and _json0/es/_ 
  without POS info.
 
 ## Related
 
 phpGlluchCoursera
 
 phpGlluchCourseTalk
 
 phpGlluchMiriadaX
 
