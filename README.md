# Placement Voting Portal

[![Live Website](https://img.shields.io/badge/Live_Website-Visit-success?style=for-the-badge)](https://aims-voting-site.netlify.app/)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)

A web-based voting and appreciation letter system for institutional placement activities. Students can vote for peers, and the portal supports certificate-style appreciation outputs with branded assets.

## Live Website

[https://aims-voting-site.netlify.app/](https://aims-voting-site.netlify.app/)

## Features

| Feature | Description |
| --- | --- |
| Voting interface | Students can submit votes through a simple web form |
| Vote processing | PHP backend handles submitted voting data |
| CSV backup | Vote records are stored for review and audit purposes |
| Appreciation assets | Includes branded certificate and recognition designs |
| Responsive pages | Usable across desktop and mobile screens |

## Certificate Assets

- AIMS print brand manual
- Bridge of Trust
- Future Forward
- Lead Link
- Linkure
- NextGen Leaders
- One Voice
- Path Finder
- Pathmaker
- Pathway Forward
- TechSpire
- Together
- Tree
- Visionary Mind

## Project Structure

```text
placement-voting-portal/
|-- index.html
|-- vote.html
|-- submit_vote.php
|-- README.md
|-- Assets/
|   |-- _AIMS of Print Brand Manual.png
|   |-- background.jpg
|   |-- BRIDGE OF TRUST.jpg
|   |-- FUTURE FORWARD.jpg
|   |-- LEAD LINK.jpg
|   |-- LINKURE.jpg
|   |-- NEXTGENLEADERS.jpg
|   |-- ONE VOICE.jpg
|   |-- PATH FINDER.jpg
|   |-- PATHMAKER.jpg
|   |-- PATHWAYFORWARD.jpg
|   |-- TECHSPIRE.jpg
|   |-- TOGETHER.jpg
|   |-- TREE.jpg
|   |-- VISIONARY MIND.jpg
```

## Requirements

- Web server with PHP support
- Modern browser
- Write permissions for CSV vote storage when hosted with backend processing enabled

## Local Usage

1. Clone the repository.
2. Place the project in a PHP-enabled server directory.
3. Open `index.html` in the browser.
4. Use `vote.html` to submit votes through the PHP handler.

## Notes

The hosted Netlify version can serve the static pages, but PHP processing requires a server that supports PHP.

## Author

Created for the AIMS Institute placement appreciation workflow.
