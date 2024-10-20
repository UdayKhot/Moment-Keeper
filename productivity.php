<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Productivity Analyzer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-400 via-teal-500 to-green-500 p-6 flex items-center justify-center">
    <div class="w-full max-w-3xl bg-white/90 backdrop-blur-md shadow-2xl rounded-3xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-teal-600 text-white p-6">
            <h1 class="text-4xl font-bold text-center">Enhanced Productivity Analyzer</h1>
        </div>
        <div class="p-6">
            <textarea id="dailyActivities" rows="6" class="w-full mb-4 bg-white/50 backdrop-blur-sm border-2 border-teal-300 focus:border-teal-500 rounded-xl transition-all duration-300 ease-in-out p-2" placeholder="Describe your day... (e.g., I worked on a project, studied for 2 hours, had a team meeting, exercised at the gym, and watched TV)"></textarea>
            <button id="analyzeButton" class="w-full mb-6 bg-gradient-to-r from-blue-500 to-teal-500 hover:from-blue-600 hover:to-teal-600 text-white font-bold py-3 rounded-xl transition-all duration-300 ease-in-out transform hover:scale-105">
                Analyze My Day
            </button>
            <div id="loadingIndicator" class="hidden flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Analyzing...
            </div>
            <div id="activityResults" class="hidden">
                <h2 class="text-3xl font-bold mb-6 text-center bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-teal-600">Your Productivity Breakdown</h2>
                <div id="activityCards" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6"></div>
                <div id="productivityScoreCard"></div>
            </div>
            <p id="noActivitiesMessage" class="hidden text-center text-gray-600 mt-4 text-lg">
                No activities detected. Try adding more specific activities.
            </p>
        </div>
    </div>

    <script>
        const activitiesData = [
            { name: "Work", icon: "💼", color: "#4169E1", percentage: 0 },
            { name: "Study", icon: "📚", color: "#32CD32", percentage: 0 },
            { name: "Exercise", icon: "🏋️", color: "#FF4500", percentage: 0 },
            { name: "Relaxation", icon: "☕", color: "#90EE90", percentage: 0 },
            { name: "Social Media", icon: "📱", color: "#FF69B4", percentage: 0 },
            { name: "Learning", icon: "🧠", color: "#9370DB", percentage: 0 },
            { name: "Cooking", icon: "🍳", color: "#FFD700", percentage: 0 },
            { name: "Chores", icon: "🧹", color: "#A9A9A9", percentage: 0 },
            { name: "Sleep", icon: "🛌", color: "#1E90FF", percentage: 0 },
            { name: "TV", icon: "📺", color: "#8B4513", percentage: 0 },
            { name: "Gaming", icon: "🎮", color: "#FF6347", percentage: 0 },
            { name: "Productive", icon: "⚡", color: "#00CED1", percentage: 0 },
        ];

        const activityKeywords = {
            Work: ["worked", "meeting", "project", "deadline", "client", "presentation", "report", "email", "conference", "brainstorm", "collaborate", "task", "office", "business", "strategy"],
            Study: ["studied", "research", "homework", "assignment", "exam", "lecture", "notes", "textbook", "quiz", "thesis", "dissertation", "library", "academic", "learning", "revision"],
            Exercise: ["exercised", "gym", "workout", "run", "jog", "yoga", "pilates", "swim", "bike", "hike", "sport", "fitness", "training", "stretching", "cardio"],
            Relaxation: ["relaxed", "meditated", "nap", "break", "rest", "unwind", "chill", "leisure", "hobby", "self-care", "massage", "spa", "mindfulness", "breathe", "decompress"],
            "Social Media": ["scrolled", "instagram", "facebook", "twitter", "tiktok", "snapchat", "linkedin", "pinterest", "reddit", "youtube", "whatsapp", "messenger", "dm", "post", "story"],
            Learning: ["learned", "course", "tutorial", "skill", "workshop", "webinar", "seminar", "class", "lecture", "training", "education", "development", "growth", "knowledge", "mastery"],
            Cooking: ["cooked", "baked", "prepared", "meal", "recipe", "kitchen", "ingredients", "dish", "cuisine", "chef", "gourmet", "culinary", "food", "nutrition", "menu"],
            Chores: ["cleaned", "laundry", "dishes", "organize", "tidy", "vacuum", "mop", "dust", "declutter", "maintenance", "repair", "errands", "grocery", "shopping", "household"],
            Sleep: ["slept", "nap", "bedtime", "woke up", "rest", "snooze", "doze", "siesta", "hibernate", "recharge", "dream", "snore", "pillow", "blanket", "pajamas"],
            TV: ["watched", "netflix", "series", "movie", "show", "binge", "stream", "episode", "documentary", "film", "television", "cable", "broadcast", "channel", "program"],
            Gaming: ["played", "game", "console", "steam", "online", "multiplayer", "rpg", "fps", "mmorpg", "esports", "twitch", "stream", "achievement", "level up", "quest"],
            Productive: ["completed", "achieved", "finished", "accomplished", "progress", "efficiency", "effective", "output", "results", "success", "goal", "milestone", "performance", "optimize", "streamline"],
        };

        function analyzeActivities(text) {
            const activities = text.toLowerCase().trim().split(/[,;.]+/);
            const activityCounts = {};

            activitiesData.forEach(activity => {
                activityCounts[activity.name] = 0;
            });

            activities.forEach(activity => {
                Object.entries(activityKeywords).forEach(([key, keywords]) => {
                    if (keywords.some(keyword => activity.includes(keyword))) {
                        activityCounts[key]++;
                    }
                });
            });

            const totalActivities = activities.length;
            return activitiesData.map(activity => ({
                ...activity,
                percentage: (activityCounts[activity.name] / totalActivities) * 100
            })).sort((a, b) => b.percentage - a.percentage);
        }

        document.getElementById('analyzeButton').addEventListener('click', () => {
            const dailyActivities = document.getElementById('dailyActivities').value;
            document.getElementById('analyzeButton').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.remove('hidden');

            setTimeout(() => {
                const results = analyzeActivities(dailyActivities);
                displayResults(results);
                document.getElementById('loadingIndicator').classList.add('hidden');
                document.getElementById('analyzeButton').classList.remove('hidden');
            }, 1500);
        });

        function displayResults(results) {
            const activityCards = document.getElementById('activityCards');
            activityCards.innerHTML = '';

            const detectedActivities = results.filter(activity => activity.percentage > 0);

            if (detectedActivities.length > 0) {
                document.getElementById('activityResults').classList.remove('hidden');
                document.getElementById('noActivitiesMessage').classList.add('hidden');

                detectedActivities.forEach(activity => {
                    const card = document.createElement('div');
                    card.className = 'overflow-hidden transform transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-lg bg-white rounded-lg';
                    card.innerHTML = `
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <div class="p-2 rounded-full" style="background-color: ${activity.color}40">
                                        ${activity.icon}
                                    </div>
                                    <span class="font-semibold text-lg">${activity.name}</span>
                                </div>
                                <span class="text-2xl font-bold" style="color: ${activity.color}">${activity.percentage.toFixed(1)}%</span>
                            </div>
                            <div class="h-3 rounded-full" style="background-color: ${activity.color}40">
                                <div class="h-3 rounded-full transition-all duration-500 ease-in-out" style="width: ${activity.percentage}%; background-color: ${activity.color}"></div>
                            </div>
                        </div>
                    `;
                    activityCards.appendChild(card);
                });

                const productiveActivities = ["Work", "Study", "Exercise", "Learning", "Chores", "Productive"];
                const productivityScore = detectedActivities
                    .filter(activity => productiveActivities.includes(activity.name))
                    .reduce((sum, activity) => sum + activity.percentage, 0);

                const productivityScoreCard = document.getElementById('productivityScoreCard');
                productivityScoreCard.innerHTML = `
                    <div class="overflow-hidden transform transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-lg bg-white rounded-lg">
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <div class="p-2 rounded-full bg-blue-100">
                                        ⚡
                                    </div>
                                    <span class="font-semibold text-lg">Productivity Score</span>
                                </div>
                                <span class="text-2xl font-bold text-blue-500">${productivityScore.toFixed(1)}%</span>
                            </div>
                            <div class="h-3 rounded-full bg-blue-100">
                                <div class="h-3 rounded-full transition-all duration-500 ease-in-out bg-blue-500" style="width: ${productivityScore}%"></div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('activityResults').classList.add('hidden');
                document.getElementById('noActivitiesMessage').classList.remove('hidden');
            }
        }
    </script>
</body>
</html>

