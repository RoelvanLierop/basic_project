<?php
/**
 * Bundeling Project installer file
 * 
 * This installer class installs the bundeling project database information;
 */

 class Installer {
    private $mysqli, $words;

    public function __construct(){
        // Word dictionairy created with ChatGPT
        $this->words = array(
            "apple", "banana", "orange", "grape", "watermelon", "pear", "peach", "plum", "cherry", "kiwi",
            "mango", "papaya", "pineapple", "coconut", "strawberry", "blueberry", "raspberry", "blackberry", "lime", "lemon",
            "apricot", "fig", "date", "tangerine", "pomegranate", "cantaloupe", "honeydew", "mandarin", "nectarine", "clementine",
            "carrot", "potato", "tomato", "onion", "garlic", "lettuce", "spinach", "broccoli", "cauliflower", "cabbage",
            "pepper", "cucumber", "zucchini", "eggplant", "asparagus", "radish", "beetroot", "pumpkin", "squash", "celery",
            "bean", "pea", "lentil", "chickpea", "walnut", "almond", "peanut", "cashew", "hazelnut", "pistachio",
            "oat", "barley", "rice", "quinoa", "millet", "wheat", "corn", "sorghum", "rye", "buckwheat",
            "bread", "cake", "biscuit", "cookie", "pie", "pasta", "noodle", "ricecake", "tortilla", "chapati",
            "milk", "cheese", "butter", "yogurt", "cream", "ice cream", "cottage cheese", "goat cheese", "feta", "mozzarella",
            "beef", "chicken", "pork", "lamb", "turkey", "fish", "shrimp", "crab", "lobster", "clam",
            "tofu", "tempeh", "seitan", "egg", "sushi", "kebab", "taco", "burger", "sandwich", "salad",
            "soup", "stew", "curry", "stir fry", "pizza", "pancake", "waffle", "omelette", "quiche", "frittata",
            "cereal", "granola", "muesli", "pudding", "jelly", "syrup", "honey", "marmalade", "jam", "sauce",
            "vinegar", "olive oil", "cooking oil", "sugar", "salt", "pepper", "spice", "herb", "seasoning", "sauce",
            "chocolate", "candy", "sweets", "gum", "chewing gum", "sour candy", "coconut candy", "marshmallow", "nougat", "fudge",
            "snack", "chips", "popcorn", "pretzel", "crackers", "nuts", "fruit", "veggie sticks", "dried fruit", "trail mix",
            "meal", "snack", "appetizer", "main course", "dessert", "course", "buffet", "brunch", "luncheon", "feast",
            "banquet", "gala", "party", "celebration", "picnic", "barbecue", "cookout", "dinner", "breakfast", "lunch",
            "café", "restaurant", "bistro", "diner", "pub", "tavern", "food truck", "market", "grocery", "supermarket",
            "shop", "store", "vendor", "stall", "stand", "booth", "counter", "kiosk", "stand", "cart",
            "kitchen", "pantry", "stock", "fridge", "freezer", "stove", "oven", "microwave", "blender", "tea",
            "coffee", "espresso", "latte", "chai", "cappuccino", "mocha", "smoothie", "juice", "soda", "water",
            "alcohol", "beer", "wine", "cocktail", "whiskey", "spirits", "liquor", "liqueur", "champagne", "punch",
            "bubbles", "sparkling", "flat", "effervescent", "dry", "sweet", "fruit juice", "mixed drink", "shot", "refresher",
            "health", "diet", "nutrition", "calorie", "vitamin", "mineral", "protein", "carbohydrate", "fat", "fiber",
            "wholesome", "natural", "organic", "fresh", "cooked", "raw", "baked", "steamed", "grilled", "fried",
            "barbecue", "smoked", "roasted", "toasted", "sautéed", "blanched", "marinated", "seasoned", "flavored", "pickled",
            "dipped", "sliced", "chopped", "cubed", "mashed", "pureed", "shredded", "grated", "ground", "whipped",
            "battered", "breaded", "glazed", "coated", "frosted", "filled", "topped", "layered", "stuffed", "rolled",
            "wrapped", "skewered", "threaded", "chunked", "diced", "poked", "spotted", "whirled", "twisted", "folded",
            "cubed", "roasted", "simmered", "stirred", "mixed", "blended", "sliced", "wedge", "segmented", "whole",
            "whole grain", "multigrain", "gluten-free", "sugar-free", "low-carb", "low-fat", "vegetarian", "vegan", "paleo", "keto",
            "Mediterranean", "high-fiber", "high-protein", "anti-inflammatory", "superfood", "healthy", "nutritious", "succulent", "savory", "sour",
            "bitter", "sweet", "spicy", "hot", "cold", "warm", "chilled", "crispy", "tender", "fluffy",
            "juicy", "syrupy", "sticky", "smooth", "crunchy", "mushy", "zesty", "refreshing", "toasty", "pungent",
            "aromatic", "fragrant", "delicious", "tasty", "mouthwatering", "flavorful", "delectable", "scrumptious", "palatable", "appetizing",
            "satisfying", "filling", "substantial", "light", "gourmet", "exquisite", "hearty", "rich", "indulgent", "luxurious",
            "affordable", "cheap", "expensive", "pricey", "bargain", "value", "costly", "reasonable", "premium", "budget",
            "celebration", "tradition", "recipe", "meal prep", "menu", "cooking", "baking", "grilling", "seasoning", "presentation", 
            "plating", "garnishing", "serving", "hosting", "catering", "entertaining", "hospitality", "culinary", "chef",
            "cooking class", "foodie", "epicure", "gourmand", "taste", "savor", "devour", "consume", "nibble", "snack",
            "share", "pair", "accompany", "compliment", "enhance", "elevate", "explore", "discover", "enjoy", "relax"
        );
    }

    public function writeConfig() {
        $data = [];
        $data['database'] = [
            'server' => htmlspecialchars(trim($_POST['dbserver'])),
            'port' => htmlspecialchars(trim($_POST['dbport'])),
            'name' => htmlspecialchars(trim($_POST['dbname'])),
            'user' => htmlspecialchars(trim($_POST['dbuser'])),
            'password' => htmlspecialchars(trim($_POST['dbpassword']))
        ];

        $conf = fopen("src/config.json", "w");
        fwrite($conf, json_encode($data));
        fclose($conf);
    }
    public function initializeDB() {
        $this->mysqli = mysqli_connect(
            config('database.server'),
            config('database.user'), 
            config('database.password'),
            config('database.name')
        );
        
        // Check connection
        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->mysqli->connect_error;
            exit();
        }
    }

    public function createTables(){
        // Create tables here
        $this->createUserTables();
        $this->createMessageTables();
    }

    private function createUserTables() {
        // Create tables here
        $this->mysqli->query("CREATE TABLE IF NOT EXISTS users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            display_name VARCHAR(64) NOT NULL
        )");

        // Create composite index which works faster when searching email of display name
        $this->mysqli->query("CREATE INDEX searchable on users (email,display_name)");
    }

    private function createMessageTables() {
        // Create tables here
        $this->mysqli->query("CREATE TABLE IF NOT EXISTS messages (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) NOT NULL,
            message TEXT NOT NULL
        )");

        // Create index which works faster when searching messages
        $this->mysqli->query("CREATE INDEX messages on messages (message)");
    }

    public function insertMessages(){
        // Create 50 messages
        for( $i = 0; $i < 50; $i++ ) {
            $message = [];
            for( $j = 0; $j < 50; $j++ ) {
                $message[] = $this->words[ ( rand(1, 51) - 1 ) ];
            }

            $this->mysqli->query("INSERT INTO messages SET user_id='1', message='" . implode( " ", $message ) . "'");
        }
    }
 }