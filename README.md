<h1 align="center">Razoyo_CarProfile</h1> 

<div align="center">
  <p>Allows a customer to search and set their car preferred car.</p>
</div>

## Table of contents

- [Installation](#installation)
- [Requirements](#requirements)
- [Usage](#usage)
- [License](#license)

## Installation

```
composer require bjwirgau/car-profile
bin/magento module:enable Razoyo_CarProfile
bin/magento setup:upgrade
```

## Requirements

* User must be logged into their account.
* Module must be enabled. This is enabled by default.
* API Endpoint must be configured. This is set upon installation.
![Config-Demo-09-17-2024](https://github.com/user-attachments/assets/6d825b9f-8da0-4a51-a9c9-909d50564c08)


## Usage

Upon login, navigate to the "My Car" tab. Here you will be able to search for you car by model. When the results show, select your car and click "Save My Car".
![Search-Demo-09-17-2024](https://github.com/user-attachments/assets/6330d764-3beb-4842-b7fc-939e6483a0d7)

Clicking the search bar twice will show a list of available models to search by as well
![Search-Dropdown-Demo-09-17-2024](https://github.com/user-attachments/assets/a634ccca-95b1-4501-bc44-915086682b14)

Once your car is saved, you can view the car details in "My Car Profile"
![Car-Profile-Demo-09-17-2024](https://github.com/user-attachments/assets/a010b3cb-6950-49be-af44-ddf5729493a4)

## License

[MIT](https://opensource.org/licenses/MIT)
