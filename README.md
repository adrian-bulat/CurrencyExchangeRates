

## Currency Sync
Set in .env file following variables:
EXCHANGE_RATE_API_URL="https://api.apilayer.com/exchangerates_data/latest"
EXCHANGE_RATE_API_KEY="x2pLxsE6yQmDS5E1VWhFJfLwNhqQGiCj"
BASE_CURRENCY="MDL"
SUPPORTED_CURRENCIES="EUR,GBP,RON,RUB,UAH,USD"

# Optional Crontab settings  

**Install crontab**  
sudo apt update
sudo apt install crontab
sudo systemctl start cron
sudo systemctl status cron

**Config logging**  
crontab -e
* * * * * php /*path-to-project*/artisan schedule:run >> /*path-to-project*/storage/logs/schedule.log 2>&1

**Ensure Artisan Commands Are Available**  
/usr/bin/php /*path-to-project*/artisan schedule:run

**Setting crontab script running periodicity**  
Adjust the time of script running in App\Console\Kernel.php

