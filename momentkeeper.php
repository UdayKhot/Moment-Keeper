<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moment Keeper - Dashboard</title>
    <style>
        /* Your existing styles... */
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(-45deg, #a8e063, #56ab2f, #00c6fb, #005bea);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 30px;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .logo {
            font-size: 2em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .option {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        .option:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .option h2 {
            margin-top: 0;
            font-size: 1.2em;
            color: #005bea;
        }
        .option p {
            margin-bottom: 0;
            font-size: 0.9em;
            color: #666;
        }
        .option-icon {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        /* Event Directory Styles */
        #event-directory {
            display: none;
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        #event-directory h1 {
            text-align: center;
            color: #005bea;
        }
        #event-form {
            display: flex;
            flex-direction: column;
        }
        #event-form input, #event-form textarea {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        #event-form button {
            padding: 10px;
            background-color: #005bea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #event-form button:hover {
            background-color: #0046a3;
        }
        .event {
            background-color: #e9f5ff;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        /* Gallery Styles */
        #gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }
        #gallery img {
            max-width: 200px;
            max-height: 150px;
            border: 2px solid #333;
        }
        #gallery-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }
        #gallery-modal .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
        }
        .modal-content input {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Moment Keeper</div>
        <h1>Dashboard</h1>
        
        <div id="dashboard" class="options">
            <div class="option" onclick="redirectToGuide()">
                <div class="option-icon">⏱️</div>
                <h2>Guide Manual</h2>
                <p>Learn How to use Moment Keeper Effectively</p>
            </div>
            
            <script>
                function redirectToGuide() {
                    window.location.href = 'test.php';
                }
            </script>
            
            <div class="option" onclick="redirectToEmotion()">
                <div class="option-icon">⏱️</div>
                <h2>Emotion Worriers</h2>
                <p>Analyze your Emotions and Stay Healthy</p>
            </div>
            
            <script>
                function redirectToEmotion() {
                    window.location.href = 'emotionanalyzer.php';
                }
            </script>
            
            <div class="option" onclick="redirectToProductivity()">
                <div class="option-icon">⏱️</div>
                <h2>Time Spent</h2>
                <p>Track and analyze your time usage</p>
            </div>
            
            <script>
                function redirectToProductivity() {
                    window.location.href = 'productivity.php';
                }
            </script>
            
            <div class="option" onclick="showGalleryModal()">
                <div class="option-icon">🖼️</div>
                <h2>Image Gallery</h2>
                <p>Upload and view your images</p>
            </div>
            <div class="option" onclick="showEventDirectory()">
                <div class="option-icon">📅</div>
                <h2>Event Directory</h2>
                <p>Browse and manage your events</p>
            </div>
        </div>
        
        <!-- Event Directory -->
        <div id="event-directory">
            <h1>Event Directory</h1>

            <form id="event-form">
                <input type="text" id="event-title" placeholder="Event Title" required>
                <textarea id="event-description" rows="3" placeholder="Event Description" required></textarea>
                <button type="button" onclick="addEvent()">Add Event</button>
            </form>

            <div id="event-list"></div>
        </div>

        <!-- Image Gallery Modal -->
        <div id="gallery-modal" class="hidden" onclick="hideGalleryModal()">
            <div class="modal-content" onclick="event.stopPropagation()">
                <h2>Image Gallery</h2>
                <div class="form-group">
                    <label for="albumName">Enter Album Name:</label>
                    <input type="text" id="albumName" placeholder="Album Name">
                </div>
                <div class="form-group">
                    <label for="albumDate">Select Album Date:</label>
                    <input type="date" id="albumDate">
                </div>
                <div class="form-group">
                    <label for="imageInput">Upload Images:</label>
                    <input type="file" id="imageInput" accept="image/*" multiple>
                </div>
                <button onclick="saveImages()">Save Images to Album</button>

                <div class="form-group">
                    <label for="searchDate">Enter Date to Retrieve Images:</label>
                    <input type="date" id="searchDate">
                </div>
                <button onclick="retrieveImages()">Retrieve Images</button>

                <div id="gallery"></div>
            </div>
        </div>

        <script>
            // Initialize events array
            let events = [];

            // Function to show the event directory
            function showEventDirectory() {
                document.getElementById("event-directory").style.display = "block";
            }

            // Function to add an event
            function addEvent() {
                const title = document.getElementById('event-title').value;
                const description = document.getElementById('event-description').value;

                if (title && description) {
                    events.push({ title, description });
                    document.getElementById('event-title').value = '';
                    document.getElementById('event-description').value = '';
                    renderEvents();
                } else {
                    alert("Please fill in both fields.");
                }
            }

            // Function to render events
            function renderEvents() {
                const eventList = document.getElementById('event-list');
                eventList.innerHTML = '';

                events.forEach((event, index) => {
                    const eventDiv = document.createElement('div');
                    eventDiv.className = 'event';
                    eventDiv.innerHTML = `<strong>${event.title}</strong><p>${event.description}</p>`;
                    eventList.appendChild(eventDiv);
                });
            }

            // Functions for image gallery
            function showGalleryModal() {
                document.getElementById('gallery-modal').style.display = 'flex';
            }

            function hideGalleryModal() {
                document.getElementById('gallery-modal').style.display = 'none';
            }

            function saveImages() {
                const albumName = document.getElementById('albumName').value;
                const albumDate = document.getElementById('albumDate').value;
                const imageInput = document.getElementById('imageInput');
                const files = imageInput.files;

                // Image upload logic here...

                alert('Images saved!');
            }

            function retrieveImages() {
                const searchDate = document.getElementById('searchDate').value;

                // Logic to retrieve images by date...

                alert('Images retrieved for ' + searchDate);
            }
        </script>
    </div>
</body>
</html>
