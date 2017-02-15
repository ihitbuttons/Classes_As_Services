# Classes As Services

This is an example of a system that abstracts objects as implementors of services. This couples an object's dependencies to services instead of any particular implementation of a service, and allows new or different implementations of a service to be used in a system both conccurently (such as one implementation of a service for legacy code, and another for newer code) and opportunistically (in a case where one implementation would be preffered over another based on preferences, for example), and for those decisions to be based in the code and consistently located. 

In addition to this, the system provides a strong contracting system between services, and a cleaner separation of business level/functional logic and error handling code. This makes the code much more maintainable, easier to read and understand, and creates less developer work in consistently implementing best practices for security and error handling. 

The system also provides a method to more granually control access to public methods inside of services, allowing to be made public externally (callable from an entry point) or just public internally (callable only from another service, and never from an entry point).   

I have included some examples to demonstrate the capabilities and features of the system, and included examples below. 

For more information and back story on why I created this, please check out my blog ([here] (http://blog.ihitbuttons.com/)).

# Examples:

1) Call the "exampleExternalMethod" in the ([Example Class](https://github.com/ihitbuttons/Classes_As_Services/blob/master/Services/Example/Implementors/Example_Class.php)): 
-----
Run:
~~~
php /{path to repo}/Examples/ServicesExample.php --service "Example" --method "exampleExternalMethod" --serviceArguments '{}' --jsonArgs '{"number":5,"string":"abcd"}'
~~~

It should print out the path of execution and a normal response. With all the defaults in place, it will use the Example_Class to implement the Example Service, and the Dependency_Class to implement the Dependency when called by the Example_Class.

2) Contract validation:
-----
Run:
~~~
php /{path to repo}/Examples/ServicesExample.php --service "Example" --method "exampleExternalMethod" --serviceArguments '{}' --jsonArgs '{"number":"dog","string":"abcd"}'
~~~

It should print out the path of execution, an error, and a stack trace of the error. In this case it will be an uncaught error with a message of "Required: number is of the wrong type. It should be of int".

If you edit the Edit the ([master include file] (https://github.com/ihitbuttons/Classes_As_Services/blob/master/Include/master.inc.php)) and change:

~~~ 
define('CURRENT_LOGGING_LEVEL', 10);
to
define('CURRENT_LOGGING_LEVEL', 1);
~~~

and run the command again, you will notice that, despite the fact an uncaught exception is thrown, the system still exits normally and formats a proper response. The information about the exception is printed in the response, but this is, of course, not required and just done for demonstration purposes. 

For the rest of the examples, you should change the logging level back.

3) External / Internal validation: 
-----
Run:
~~~
php /{path to repo}/Examples/ServicesExample.php --service "Example" --method "exampleInternallMethod" --serviceArguments '{}' --jsonArgs '{"number":"dog","string":"abcd"}'
~~~

It should print out the path of execution, an error, and (like the example above) a stack trace of the error. In the default implementation of the Example service, this is an internal only method and is not callable from the entry point.

4) Developer called dependency override: 
-----
Run:
~~~
php /{path to repo}/Examples/ServicesExample.php --service "Example" --method "exampleExternalMethod" --serviceArguments '{"serviceOverrideArray":{"Dependency":"Override"}}' --jsonArgs '{"number":5,"string":"abcd"}'
~~~

It should print out the path of execution. Notice that the Override_Class is used in implementing the Dependency Service instead of the Dependency_Class. This also changes the final output.

5) Alternate Service Implementation: 
-----

Edit the ([master include file] (https://github.com/ihitbuttons/Classes_As_Services/blob/master/Include/master.inc.php))

Change:
~~~ 
define('CURRENT_VERSION', '1');
to
define('CURRENT_VERSION', '2');
~~~

Run:
~~~
php /{path to repo}/Examples/ServicesExample.php --service "Example" --method "exampleExternalMethod" --serviceArguments '{}' --jsonArgs '{"number":5,"string":"abcd"}'
~~~

It should print out the path of execution and a normal response. Notice that Example_Alt_Class is used to implement the Example service instead of Example_Class.

6) External / Internal validation: 
-----
Using the
~~~
define('CURRENT_VERSION', '2');
~~~

Run:
~~~
php /{path to repo}/Examples/ServicesExample.php --service "Example" --method "exampleInternallMethod" --serviceArguments '{}' --jsonArgs '{"number":"dog","string":"abcd"}'
~~~

It should print out the path of execution and a normal response. Notice that Example_Alt_Class is used to implement the Example Service instead of the Example_Class. In this implementation of the Example service, this method is a publically accessable method.





