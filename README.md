# mudmod
Backend software for Mudmod System

## Features
* Multiple Roles
    * Admin
        * issue command
            * ENABLE | DISABLE \<PIN\>
            * PING
            * COUNT
        * send message
            * \<PIN\> CODES
        * broadcast message
            * @ALL \<message\>
            * @GROUP1 \<message\>
    * Agent
        * send message
            * \<code\> \<name\>
        * issue command
            * GC \<amount\> \<code\>
            * INSTRUCTION | INST <\message\>
            * GO | START | STOP | | HALT | EXTEND | MORE
    * Cashier
        * issue command
            * CREDIT \<amount\> \<mobile\> \<PIN\>
    * Subscriber
        * send message
            * \<code\> \<name\>
            * \#B-CODE | \#BCODE | \#CODE
            * \<message\>
            
## Units Tested
* Role - [x]
* Permission - [x]
* Setting - [x]
* Contact - [x]

## Features Tested
* Auto-generated Role Access Codes - [x]
* Enlist from Access Code - [x]
* Remote Control
    * Application Maintenance Mode
* Analytics
* Connection Status
* Broadcast Messages
* On-Demand Generation of GC
* On-Demand Drafting of GC Instructions
* On-Demand START-STOP of GC Redemption
* Registration via GC Code & Name
* Auto-generated B-Code
* On-Demand B-Code Request
* Messaging to Upline
