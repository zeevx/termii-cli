<p align="center">
    <img title="Termii" src="https://termii.com/assets/images/logo.png"/>
</p>

## Flutterwave CLI
The TERMII CLI application helps you Set up, test, and manage your Termii integration directly from the terminal.

## Setup:
1. Ensure you have composer installed on your computer. You can confirm this by running `composer -v` from your terminal. If you don't have it installed, you can download and install it using the [Composer](https://getcomposer.org/download/) docs.
2. After successful installation of composer, run the command on your terminal `composer global require zeevx/termii-cli` to require it globally
3. Run on your terminal `termii` to view the available commands.  
4. Setup your CLI by adding your Flutterwave keys (Test and Live).  
   Run `termii key`.
<!-- Run `termii-key`.   -->
**NB:** This is a one time setup and your keys are stored in your computer and not sent to any server.

## Usage:

###  Check your balance on Termii
You can check your termii balance.
Run `termii balance`

### Reports for messages sent across the sms, voice & whatsapp channels
You can check reports for messages sent across the sms, voice & whatsapp channels.
Run `termii history`

### Detect if a number is fake or has ported to a new network
You can check if a number is fake or has ported to a new network.
Run `termii status` and follow the prompt

### Verify phone numbers and automatically detect their status
You can verify phone numbers and automatically detect their status.
Run `termii search` and follow the prompt

### Retrieve the status of all registered sender ID
You can retrieve the status of all registered sender ID.
Run `termii sender-id:fetch`

### Request a new sender ID
You can request a new sender ID.
Run `termii sender-id:request` and follow the prompt


Adams Paul  
adamsohiani@gmail.com
