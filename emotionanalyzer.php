<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Emotion Analyzer</title>
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
<body class="min-h-screen bg-gradient-to-br from-purple-400 via-pink-500 to-red-500 p-6 flex items-center justify-center">
    <div class="w-full max-w-3xl bg-white/90 backdrop-blur-md shadow-2xl rounded-3xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
            <h1 class="text-4xl font-bold text-center">Enhanced Emotion Analyzer</h1>
        </div>
        <div class="p-6">
            <textarea id="dailyActivities" rows="6" class="w-full mb-4 bg-white/50 backdrop-blur-sm border-2 border-purple-300 focus:border-purple-500 rounded-xl transition-all duration-300 ease-in-out p-2" placeholder="Describe your day... (e.g., I played soccer, watched a funny movie, argued with a friend, and meditated before bed)"></textarea>
            <button id="analyzeButton" class="w-full mb-6 bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600 text-white font-bold py-3 rounded-xl transition-all duration-300 ease-in-out transform hover:scale-105">
                Analyze My Day
            </button>
            <div id="loadingIndicator" class="hidden flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Analyzing...
            </div>
            <div id="emotionResults" class="hidden">
                <h2 class="text-3xl font-bold mb-6 text-center bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">Your Emotional Breakdown</h2>
                <div id="emotionCards" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
            </div>
            <p id="noEmotionsMessage" class="hidden text-center text-gray-600 mt-4 text-lg">
                No emotions detected. Try adding more specific activities.
            </p>
        </div>
    </div>

    <script>
        const emotionsData = [
            { name: "Happy", icon: "😊", color: "#FFD700", percentage: 0 },
            { name: "Sad", icon: "😢", color: "#4169E1", percentage: 0 },
            { name: "Angry", icon: "😠", color: "#FF4500", percentage: 0 },
            { name: "Relaxed", icon: "☕", color: "#90EE90", percentage: 0 },
            { name: "Excited", icon: "✨", color: "#FF69B4", percentage: 0 },
            { name: "Exhausted", icon: "🔋", color: "#8B4513", percentage: 0 },
            { name: "Bored", icon: "😐", color: "#A9A9A9", percentage: 0 },
            { name: "Frustrated", icon: "⚡", color: "#FF6347", percentage: 0 },
            { name: "Anxious", icon: "⚠️", color: "#9370DB", percentage: 0 },
            { name: "Confident", icon: "🏆", color: "#1E90FF", percentage: 0 },
            { name: "Grateful", icon: "❤️", color: "#32CD32", percentage: 0 },
            { name: "Surprised", icon: "💡", color: "#FF1493", percentage: 0 },
        ];

        const emotionActivities = {
            Happy: ["played", "watched", "listened", "danced", "partied", "laughed", "celebrated", "enjoyed", "smiled", "hugged", "complimented", "succeeded", "accomplished", "won", "relaxed"],
            Sad: ["cried", "argued", "lost", "failed", "missed", "grieved", "regretted", "disappointed", "hurt", "longed", "mourned", "suffered", "felt lonely", "felt rejected", "felt heartbroken"],
            Angry: ["yelled", "fought", "hated", "envied", "resented", "argued", "criticized", "blamed", "insulted", "complained", "protested", "objected", "felt betrayed", "felt cheated", "felt disrespected"],
            Relaxed: ["meditated", "yoga", "read", "napped", "relaxed", "breathed deeply", "took a bath", "massaged", "stretched", "walked in nature", "listened to calm music", "practiced mindfulness", "did aromatherapy", "gardened", "stargazed"],
            Excited: ["traveled", "explored", "discovered", "achieved", "celebrated", "anticipated", "planned", "started a new project", "learned something new", "met new people", "tried a new experience", "received good news", "won a prize", "got promoted", "fell in love"],
            Exhausted: ["worked", "studied", "exercised", "cleaned", "cooked", "overworked", "pulled an all-nighter", "ran errands", "took care of others", "rushed", "multitasked", "traveled long distances", "moved house", "dealt with stress", "recovered from illness"],
            Bored: ["waited", "stood", "sat", "did nothing", "procrastinated", "felt uninspired", "watched uninteresting TV", "attended a dull meeting", "repeated routine tasks", "felt unchallenged", "lacked stimulation", "felt unproductive", "daydreamed", "scrolled mindlessly", "felt restless"],
            Frustrated: ["struggled", "failed", "got stuck", "encountered obstacles", "faced setbacks", "dealt with technical issues", "misunderstood instructions", "experienced delays", "missed deadlines", "felt incompetent", "had writer's block", "faced bureaucracy", "felt powerless", "couldn't solve a problem", "felt unheard"],
            Anxious: ["worried", "feared", "was nervous", "panicked", "felt stressed", "overthought", "had racing thoughts", "felt uneasy", "anticipated problems", "felt insecure", "doubted myself", "felt overwhelmed", "had performance anxiety", "feared the unknown", "felt social anxiety"],
            Confident: ["achieved", "succeeded", "felt proud", "led a team", "gave a presentation", "overcame a challenge", "stood up for myself", "made a difficult decision", "received praise", "mastered a skill", "felt self-assured", "took initiative", "expressed my opinion", "felt competent", "trusted my instincts"],
            Grateful: ["thanked", "appreciated", "felt lucky", "counted blessings", "expressed gratitude", "acknowledged help", "felt fortunate", "recognized kindness", "valued relationships", "enjoyed simple pleasures", "felt supported", "overcame hardship", "received unexpected gifts", "experienced good health", "felt loved"],
            Surprised: ["was shocked", "was amazed", "was astonished", "discovered unexpectedly", "received surprising news", "experienced a plot twist", "was caught off guard", "had an epiphany", "witnessed a rare event", "encountered the unexpected", "was pleasantly surprised", "felt wonder", "had a eureka moment", "was taken aback", "experienced a sudden realization"],
        };

        function analyzeEmotions(text) {
            const activities = text.toLowerCase().trim().split(/[,;.]+/);
            const emotionCounts = {};

            emotionsData.forEach(emotion => {
                emotionCounts[emotion.name] = 0;
            });

            activities.forEach(activity => {
                Object.entries(emotionActivities).forEach(([emotion, keywords]) => {
                    if (keywords.some(keyword => activity.includes(keyword))) {
                        emotionCounts[emotion]++;
                    }
                });
            });

            const totalActivities = activities.length;
            return emotionsData.map(emotion => ({
                ...emotion,
                percentage: (emotionCounts[emotion.name] / totalActivities) * 100
            })).sort((a, b) => b.percentage - a.percentage);
        }

        document.getElementById('analyzeButton').addEventListener('click', () => {
            const dailyActivities = document.getElementById('dailyActivities').value;
            document.getElementById('analyzeButton').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.remove('hidden');

            setTimeout(() => {
                const results = analyzeEmotions(dailyActivities);
                displayResults(results);
                document.getElementById('loadingIndicator').classList.add('hidden');
                document.getElementById('analyzeButton').classList.remove('hidden');
            }, 1500);
        });

        function displayResults(results) {
            const emotionCards = document.getElementById('emotionCards');
            emotionCards.innerHTML = '';

            const detectedEmotions = results.filter(emotion => emotion.percentage > 0);

            if (detectedEmotions.length > 0) {
                document.getElementById('emotionResults').classList.remove('hidden');
                document.getElementById('noEmotionsMessage').classList.add('hidden');

                detectedEmotions.forEach(emotion => {
                    const card = document.createElement('div');
                    card.className = 'overflow-hidden transform transition-all duration-300 ease-in-out hover:scale-105 hover:shadow-lg bg-white rounded-lg';
                    card.innerHTML = `
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <div class="p-2 rounded-full" style="background-color: ${emotion.color}40">
                                        ${emotion.icon}
                                    </div>
                                    <span class="font-semibold text-lg">${emotion.name}</span>
                                </div>
                                <span class="text-2xl font-bold" style="color: ${emotion.color}">${emotion.percentage.toFixed(1)}%</span>
                            </div>
                            <div class="h-3 rounded-full" style="background-color: ${emotion.color}40">
                                <div class="h-3 rounded-full transition-all duration-500 ease-in-out" style="width: ${emotion.percentage}%; background-color: ${emotion.color}"></div>
                            </div>
                        </div>
                    `;
                    emotionCards.appendChild(card);
                });
            } else {
                document.getElementById('emotionResults').classList.add('hidden');
                document.getElementById('noEmotionsMessage').classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
