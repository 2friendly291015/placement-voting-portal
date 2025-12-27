# Placement Voting Portal

A dynamic web-based voting and appreciation system built for institutional placement activities. This project enables students to vote for their peers and generate digital appreciation letters with custom certificate designs.

## Features

✨ **Core Functionality:**
- Interactive online voting interface
- Vote submission and validation
- Digital appreciation letter generation
- CSV data backup for vote records
- Responsive design for desktop and mobile

🎯 **Certificate Assets:**
- AIMS Brand branding elements
- Pathway Forward certificate
- TechSpire certificate
- Together certificate
- Tree certificate
- Visionary Mind certificate
- Professional print-ready brand guidelines

## Project Structure

```
placement-voting-portal/
├── index.html              # Main landing page
├── vote.html               # Voting interface
├── submit_vote.php         # Backend vote processing
├── votes_backup.csv        # Vote records backup
└── Assets/                 # Certificate and branding images
    ├── AIMSBRAND.jpg
    ├── PATHWAYFORWARD.jpg
    ├── TECHSPIRE.jpg
    ├── TOGETHER.jpg
    ├── TREE.jpg
    ├── VISIONARY MIND.jpg
    └── AIMS of Print Brand Manual.png
```

## Technologies Used

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Data Storage:** CSV files
- **Design:** Responsive web design principles

## How It Works

1. **Navigate to the homepage** (`index.html`) to access the voting portal
2. **Vote for peers** using the voting interface (`vote.html`)
3. **Submit votes** which are processed by the PHP backend (`submit_vote.php`)
4. **Generate appreciation letters** with certificate designs
5. **Data is backed up** in CSV format for records

## Installation & Usage

### Requirements
- Web server with PHP support
- Modern web browser
- File upload capabilities

### Steps
1. Clone or download this repository
2. Upload files to your web server
3. Ensure PHP processing is enabled
4. Navigate to `index.html` in your browser
5. Start voting and generating appreciation letters

## Data Storage

Vote records are stored in `votes_backup.csv` for backup and audit purposes. The CSV file maintains records of all submitted votes with timestamps.

## Features by Design

- **User-Friendly Interface:** Clean, intuitive voting experience
- **Data Security:** Backend validation of all submissions
- **Professional Output:** Branded certificates and appreciation letters
- **Scalable:** Designed to handle multiple voting cycles

## Author

Created as part of the AIMS Institute placement program.

## License

This project is available for educational and institutional use.

---

**Note:** This project was created to streamline the placement appreciation process and recognize student achievements within the institution.
