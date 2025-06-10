# Motion News 📰

**A responsive news CMS built with PHP and Tailwind CSS, featuring article management, genre filtering, and markdown support for content creators.**

## 🚀 Features

- **📝 Article Management**: Create, edit, and delete news articles with rich text support
- **🔍 Smart Filtering**: Filter articles by genre and sort by date
- **📱 Responsive Design**: Mobile-first interface built with Tailwind CSS
- **🎨 Modern UI**: Clean, intuitive dashboard for content management
- **🔒 Secure**: Input validation and sanitization for data security
- **⚡ Fast**: JSON-based storage for quick data retrieval

## 🛠 Tech Stack

- **Backend**: PHP 7.4+
- **Frontend**: HTML5, Tailwind CSS, Vanilla JavaScript
- **Storage**: JSON file system
- **Server**: Apache/XAMPP compatible

## 📁 Project Structure

```
news-app/
├── index.php           # Main news display page
├── dashboard.php       # Admin dashboard
├── articleCreate.php   # Article creation handler
├── articleUpdate.php   # Article editing functionality
├── articleDelete.php   # Article deletion handler
├── articles.json       # News data storage
├── genre.txt          # Available news categories
└── .env               # Environment configuration
```

## 🚀 Quick Start

1. **Clone the repository**

   ```bash
   git clone https://github.com/sarvinshrivastava/Motion-News.git
   cd Motion-News
   ```

2. **Set up XAMPP/Apache**

   - Place the project in your web server directory (`htdocs` for XAMPP)
   - Start Apache server

3. **Configure Environment**

   - Copy `.env.example` to .env
   - Update configuration as needed

4. **Access the Application**
   - News Homepage: `http://localhost/<foldername>/index.php`
   - Admin Dashboard: `http://localhost/<foldername>/dashboard.php`

## 📖 Usage

### For Readers

- Browse articles on the homepage
- Filter by genre (Politics, Sports, Technology, Entertainment, Business, International)
- Sort articles by latest or oldest

### For Content Creators

- Access the dashboard to manage articles
- Create new articles with markdown support
- Edit existing articles
- Delete unwanted content

## 🔧 Configuration

### Adding New Genres

Edit genre.txt and add one genre per line:

```
Politics
Sports
Technology
Entertainment
Business
International
```

### Environment Variables

Configure your .env file:

```env
APP_NAME="Motion News"
DEBUG=true
```

## 📊 Sample Data

The project comes with sample articles covering:

- **Politics**: NATO and Russia relations
- **International**: Operation Sindoor military coverage
- **Entertainment**: Bollywood film releases
- **Business**: AI and sustainability trends

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🐛 Bug Reports

Found a bug? Please open an issue with:

- Description of the bug
- Steps to reproduce
- Expected vs actual behavior
- Screenshots (if applicable)

## 📞 Support

For support and questions:

- Create an issue on GitHub
- Contact: [sarvin5124@gmail.com]

---

**Made with ❤️ using PHP and Tailwind CSS**
