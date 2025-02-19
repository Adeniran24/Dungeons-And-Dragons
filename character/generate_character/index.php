<?php
session_start(); // Start the session

// Check if the user is logged in by verifying session variables
if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    // If the user is not logged in, redirect to login page
    header("Location: ../main/login.php");
    exit();
} else {
    // The user is logged in, you can use the session variables
    $is_logged_in = true;
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Store the profile image URL in session (assume profile picture is already set in the session)
    $profil_img['profile_picture'] = $_SESSION['profile_picture']; 
    
    // Optional: verify token if using cookie for added security
    if (isset($_COOKIE['auth_token']) && $_COOKIE['auth_token'] !== $_SESSION['token']) {
        // Invalidate session if the token does not match
        session_unset();
        session_destroy();
        header("Location: ../main/login.php");
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="Description"
      content="Levi Blodgett, D&D, D&D Character Generator, Dungeons and Dragons, Dungeons and Dragons Character Generator"
    />
    <link rel="icon" type="image/ico" href="img/favicon.png" />
    <link href="styles.css" rel="stylesheet" />
    <title>D&D Website</title>
    <link rel="stylesheet" href="../../main/index.css">
    <script src="index.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>



    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid">
        <a class="navbar-brand" href="../../main/index.php" style="color: rgb(255, 0, 0); background-color: black; padding: 10px 20px; border-radius: 25px; font-family: 'Cinzel', serif; font-weight: bold;">
    D&D Ultimate Tool
</a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../../character/character.php">Characters</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../../wiki/wiki.php">Wiki</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../../dmtool/dmTools.php">DM Tools</a>
              </li>

            </ul>
            <form class="d-flex">
                <?php if ($is_logged_in): ?>
                <!-- If the user is logged in, the profile button with their username and image will be shown -->
                <a style="display: block; color:yellow;" id="Logged" href="../../profile/profil.php" >
                    <!-- Display the user's profile image -->
                    <img class="profKep" id="profkep" 
                        src="<?php echo htmlspecialchars('../' . ($_SESSION['profile_picture'] ?? '../../defaults/profile_picture.jpg')); ?>" alt="Profile Image">
                        <?php echo htmlspecialchars($username); ?>
                </a>
                <?php else: ?>
                    <!-- Ha nincs bejelentkezve, akkor a Login/Register gomb jelenik meg -->
                    <a style="display: block;" id="LogReg" class="btn btn-outline-warning" href="../main/login.php">Login/Register</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>

  </head>
  

  <body id="top">
    <!-- Top button section, which is all centered and  not shown on print screen -->
    <div class="no-print">
      <textarea id="no-show"></textarea>
      <div id="testing"></div>
      <div class="center">
        <button
          class="top_buttons no_select"
          onclick="switchScripts()"
          id="top_button"
        >
          Switch to Random Version
        </button>
      </div>
      <div class="center">
        <button
          class="top_buttons no_select"
          onclick="generate_new_character(roll_version)"
        >
          New stat roller character
        </button>
        <button
          class="top_buttons no_select"
          onclick="generate_new_character(standard_version)"
        >
          New standard array character
        </button>
        <button
          class="top_buttons"
          id="last_button"
          onclick="generate_new_character(pointbuy_version)"
        >
          New point buy character
        </button>
      </div>
    </div>

    <!-- Start of dnd section, which would be hidden/shown upon showing information apge -->
    <div id="dnd">
      <!-- Start of dropdown section, allowing user to select individual or multiple options instead of all options -->
      <div id="center_dropdowns" class="no-print">
        <div class="dropdowns cell_A">
          <div id="race_list" class="dropdown-check-list">
            <h2 class="dropdown_section">Race:</h2>
            <span id="Race_Text" class="anchor">Select Race:</span>
            <ul id="race_dropdown" class="items">
              <label>
                <li class="spacer">
                  <input
                    type="checkbox"
                    value="Random"
                    id="race_random"
                    checked
                  />Random
                </li>
              </label>
              <div id="dragonborn_list">
                <span class="chain">
                  <li class="race_grabber race_dropdown_option_checker">
                    <input
                      id="dragonborn_checkbox"
                      type="checkbox"
                      value="Dragonborn"
                      class="race_class_2"
                    />Dragonborn
                  </li>
                </span>
                <ul id="dragonborn_dropdown" class="huge_margins">
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Black Dragonborn"
                        class="race_class"
                      />Black Dragonborn
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Blue Dragonborn"
                        class="race_class"
                      />Blue Dragonborn
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Brass Dragonborn"
                        class="race_class"
                      />Brass Dragonborn
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Bronze Dragonborn"
                        class="race_class"
                      />Bronze Dragonborn
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Copper Dragonborn"
                        class="race_class"
                      />Copper Dragonborn
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Gold Dragonborn"
                        class="race_class"
                      />Gold Dragonborn
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Green Dragonborn"
                        class="race_class"
                      />Green Dragonborn
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Red Dragonborn"
                        class="race_class"
                      />Red Dragonborn
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Silver Dragonborn"
                        class="race_class"
                      />Silver Dragonborn
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="White Dragonborn"
                        class="race_class"
                      />White Dragonborn
                    </li>
                  </label>
                </ul>
              </div>
              <div id="dwarf_list">
                <span class="chain">
                  <li class="race_grabber race_dropdown_option_checker">
                    <input
                      id="dwarf_checkbox"
                      type="checkbox"
                      value="Dwarf"
                      class="race_class_2"
                    />Dwarf
                  </li>
                </span>
                <ul id="dwarf_dropdown" class="huge_margins">
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Hill Dwarf"
                        class="race_class"
                      />Hill Dwarf
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Mountain Dwarf"
                        class="race_class"
                      />Mountain Dwarf
                    </li>
                  </label>
                </ul>
              </div>
              <div id="elf_list">
                <span class="chain">
                  <li class="race_grabber race_dropdown_option_checker">
                    <input
                      id="elf_checkbox"
                      type="checkbox"
                      value="Elf"
                      class="race_class_2"
                    />Elf
                  </li>
                </span>
                <ul id="elf_dropdown" class="huge_margins">
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Dark Elf (Drow)"
                        class="race_class"
                      />Dark Elf (Drow)
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="High Elf"
                        class="race_class"
                      />High Elf
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Wood Elf"
                        class="race_class"
                      />Wood Elf
                    </li>
                  </label>
                </ul>
              </div>
              <div id="gnome_list">
                <span class="chain">
                  <li class="race_grabber race_dropdown_option_checker">
                    <input
                      id="gnome_checkbox"
                      type="checkbox"
                      value="Gnome"
                      class="race_class_2"
                    />Gnome
                  </li>
                </span>
                <ul id="gnome_dropdown" class="huge_margins">
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Forest Gnome"
                        class="race_class"
                      />Forest Gnome
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Rock Gnome"
                        class="race_class"
                      />Rock Gnome
                    </li>
                  </label>
                </ul>
              </div>
              <label>
                <li class="spacer race_grabber race_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="HalfElf"
                    class="race_class race_class_2"
                  />Half-Elf
                </li>
              </label>
              <label>
                <li class="spacer race_grabber race_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="HalfOrc"
                    class="race_class race_class_2"
                  />Half-Orc
                </li>
              </label>
              <div id="halfling_list">
                <span class="chain">
                  <li class="race_grabber race_dropdown_option_checker">
                    <input
                      id="halfling_checkbox"
                      type="checkbox"
                      value="Halfling"
                      class="race_class_2"
                    />Halfling
                  </li>
                </span>
                <ul id="halfling_dropdown" class="huge_margins">
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Lightfoot Halfling"
                        class="race_class"
                      />Lightfoot Halfling
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Stout Halfling"
                        class="race_class"
                      />Stout Halfling
                    </li>
                  </label>
                </ul>
              </div>
              <div id="human_list">
                <span class="chain">
                  <li class="race_grabber race_dropdown_option_checker">
                    <input
                      id="human_checkbox"
                      type="checkbox"
                      value="Human"
                      class="race_class_2"
                    />Human
                  </li>
                </span>
                <ul id="human_dropdown" class="huge_margins">
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Human (Calishite)"
                        class="race_class"
                      />Human (Calishite)
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Human (Chondathan)"
                        class="race_class"
                      />Human (Chondathan)
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Human (Damaran)"
                        class="race_class"
                      />Human (Damaran)
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Human (Illuskan)"
                        class="race_class"
                      />Human (Illuskan)
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Human (Mulan)"
                        class="race_class"
                      />Human (Mulan)
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Human (Rashemi)"
                        class="race_class"
                      />Human (Rashemi)
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Human (Shou)"
                        class="race_class"
                      />Human (Shou)
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Human (Tethyrian)"
                        class="race_class"
                      />Human (Tethyrian)
                    </li>
                  </label>
                  <label>
                    <li class="subrace_dropdown_option_checker">
                      <input
                        type="checkbox"
                        value="Human (Turami)"
                        class="race_class"
                      />Human (Turami)
                    </li>
                  </label>
                </ul>
              </div>
              <label>
                <li class="race_grabber race_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Tiefling"
                    class="race_class race_class_2"
                  />Tiefling
                </li>
              </label>
            </ul>
          </div>
        </div>

        <div class="dropdowns cell_B">
          <div id="background_list" class="dropdown-check-list">
            <h2 class="dropdown_section">Background:</h2>
            <span id="Background_Text" class="anchor">Select Background:</span>
            <ul id="background_dropdown" class="items">
              <label>
                <li>
                  <input
                    type="checkbox"
                    value="Random"
                    id="background_random"
                    checked
                  />Random
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Acolyte"
                    class="background_class"
                  />Acolyte
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Charlatan"
                    class="background_class"
                  />Charlatan
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Criminal"
                    class="background_class"
                  />Criminal
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Entertainer"
                    class="background_class"
                  />Entertainer
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Folk Hero"
                    class="background_class"
                  />Folk Hero
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Gladiator"
                    class="background_class"
                  />Gladiator
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Guild Artisan"
                    class="background_class"
                  />Guild Artisan
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Guild Merchant"
                    class="background_class"
                  />Guild Merchant
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Hermit"
                    class="background_class"
                  />Hermit
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Knight"
                    class="background_class"
                  />Knight
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Noble"
                    class="background_class"
                  />Noble
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Outlander"
                    class="background_class"
                  />Outlander
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Pirate"
                    class="background_class"
                  />Pirate
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Sage"
                    class="background_class"
                  />Sage
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Sailor"
                    class="background_class"
                  />Sailor
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Soldier"
                    class="background_class"
                  />Soldier
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Spy"
                    class="background_class"
                  />Spy
                </li>
              </label>
              <label>
                <li class="background_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Urchin"
                    class="background_class"
                  />Urchin
                </li>
              </label>
            </ul>
          </div>
        </div>
        <div class="dropdowns cell_C">
          <div id="class_list" class="dropdown-check-list">
            <h2 class="dropdown_section">Class:</h2>
            <span id="Class_Text" class="anchor">Select Class:</span>
            <ul id="class_dropdown" class="items">
              <label>
                <li>
                  <input
                    type="checkbox"
                    value="Random"
                    id="class_random"
                    checked
                  />Random
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Barbarian"
                    class="class_class"
                  />Barbarian
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input type="checkbox" value="Bard" class="class_class" />Bard
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Cleric"
                    class="class_class"
                  />Cleric
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Druid"
                    class="class_class"
                  />Druid
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Fighter"
                    class="class_class"
                  />Fighter
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input type="checkbox" value="Monk" class="class_class" />Monk
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Paladin"
                    class="class_class"
                  />Paladin
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Ranger"
                    class="class_class"
                  />Ranger
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Rogue"
                    class="class_class"
                  />Rogue
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Sorcerer"
                    class="class_class"
                  />Sorcerer
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Warlock"
                    class="class_class"
                  />Warlock
                </li>
              </label>
              <label>
                <li class="class_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Wizard"
                    class="class_class"
                  />Wizard
                </li>
              </label>
            </ul>
          </div>
        </div>
        <div class="dropdowns cell_D">
          <div id="alignment_list" class="dropdown-check-list">
            <h2 class="dropdown_section">Alignment:</h2>
            <span id="Alignment_Text" class="anchor">Select Alignment:</span>
            <ul id="alignment_dropdown" class="items">
              <label>
                <li>
                  <input
                    type="checkbox"
                    value="Random"
                    id="alignment_random"
                    checked
                  />Random
                </li>
              </label>
              <label>
                <li class="alignment_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Lawful Good"
                    class="alignment_class"
                  />Lawful Good
                </li>
              </label>
              <label>
                <li class="alignment_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Neutral Good"
                    class="alignment_class"
                  />Neutral Good
                </li>
              </label>
              <label>
                <li class="alignment_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Chaotic Good"
                    class="alignment_class"
                  />Chaotic Good
                </li>
              </label>
              <label>
                <li class="alignment_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Lawful Neutral"
                    class="alignment_class"
                  />Lawful Neutral
                </li>
              </label>
              <label>
                <li class="alignment_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Neutral Neutral"
                    class="alignment_class"
                  />True Neutral
                </li>
              </label>
              <label>
                <li class="alignment_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Chaotic Neutral"
                    class="alignment_class"
                  />Chaotic Neutral
                </li>
              </label>
              <label>
                <li class="alignment_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Lawful Evil"
                    class="alignment_class"
                  />Lawful Evil
                </li>
              </label>
              <label>
                <li class="alignment_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Neutral Evil"
                    class="alignment_class"
                  />Neutral Evil
                </li>
              </label>
              <label>
                <li class="alignment_dropdown_option_checker">
                  <input
                    type="checkbox"
                    value="Chaotic Evil"
                    class="alignment_class"
                  />Chaotic Evil
                </li>
              </label>
            </ul>
          </div>
        </div>
      </div>

      <!--- Position background pg. 1 -->
      <div id="p1" class="pageArea">
        <!-- Begin page background -->
        <div id="pg1Overlay"></div>
        <div id="pg1">
          <object
            width="935"
            height="1210"
            data="svg\1st_page.svg"
            type="image/svg+xml"
            id="pdf1"
          ></object>
        </div>
        <!-- End page background -->

        <!-- Begin text definitions (Positioned/styled in CSS) -->
        <div id="t1_1" class="t s1_1">RACE</div>
        <div id="t2_1" class="t s1_1">CLASS &amp; LEVEL</div>
        <div id="t3_1" class="t s1_1">PLAYER NAME</div>
        <div id="t4_1" class="t s1_1">CHARACTER NAME</div>
        <div id="t5_1" class="t s1_1">BACKGROUND</div>
        <div id="t6_1" class="t s1_1">EXPERIENCE POINTS</div>
        <div id="t7_1" class="t s1_1">ALIGNMENT</div>
        <div id="t8_1" class="t s2_1"></div>
        <div id="t9_1" class="t s3_1">Hit Point Maximum</div>
        <div id="ta_1" class="t s1_1">Strength</div>
        <div id="tb_1" class="t s1_1">Dexterity</div>
        <div id="tc_1" class="t s1_1">Constitution</div>
        <div id="td_1" class="t s1_1">Intelligence</div>
        <div id="te_1" class="t s1_1">Wisdom</div>
        <div id="tf_1" class="t s1_1">Charisma</div>
        <div id="tg_1" class="t s4_1">CP</div>
        <div id="th_1" class="t s4_1">EP</div>
        <div id="ti_1" class="t s4_1">PP</div>
        <div id="tj_1" class="t s4_1">GP</div>
        <div id="tk_1" class="t s4_1">SP</div>
        <div id="tl_1" class="t s5_1">PASSIVE WISDOM (PERCEPTION)</div>
        <div id="tm_1" class="t s6_1">EQUIPMENT</div>
        <div id="tn_1" class="t s6_1">OTHER PROFICIENCIES &amp; LANGUAGES</div>
        <div id="to_1" class="t s6_1">ATTACKS &amp; SPELLCASTING</div>
        <div id="tp_1" class="t s6_1">FEATURES &amp; TRAITS</div>
        <div id="tq_1" class="t s1_1">Acrobatics</div>
        <div id="tr_1" class="t s7_1">(Dex)</div>
        <div id="ts_1" class="t s1_1">Animal Handling</div>
        <div id="tt_1" class="t s7_1">(Wis)</div>
        <div id="tu_1" class="t s1_1">Arcana</div>
        <div id="tv_1" class="t s7_1">(Int)</div>
        <div id="tw_1" class="t s1_1">Athletics</div>
        <div id="tx_1" class="t s7_1">(Str)</div>
        <div id="ty_1" class="t s1_1">Deception</div>
        <div id="tz_1" class="t s7_1">(Cha)</div>
        <div id="t10_1" class="t s1_1">History</div>
        <div id="t11_1" class="t s7_1">(Int)</div>
        <div id="t12_1" class="t s1_1">Insight</div>
        <div id="t13_1" class="t s7_1">(Wis)</div>
        <div id="t14_1" class="t s1_1">Intimidation</div>
        <div id="t15_1" class="t s7_1">(Cha)</div>
        <div id="t16_1" class="t s1_1">Investigation</div>
        <div id="t17_1" class="t s7_1">(Int)</div>
        <div id="t18_1" class="t s1_1">Medicine</div>
        <div id="t19_1" class="t s7_1">(Wis)</div>
        <div id="t1a_1" class="t s1_1">Nature</div>
        <div id="t1b_1" class="t s7_1">(Int)</div>
        <div id="t1c_1" class="t s1_1">Perception</div>
        <div id="t1d_1" class="t s7_1">(Wis)</div>
        <div id="t1e_1" class="t s1_1">Performance</div>
        <div id="t1f_1" class="t s7_1">(Cha)</div>
        <div id="t1g_1" class="t s1_1">Persuasion</div>
        <div id="t1h_1" class="t s7_1">(Cha)</div>
        <div id="t1i_1" class="t s1_1">Religion</div>
        <div id="t1j_1" class="t s7_1">(Int)</div>
        <div id="t1k_1" class="t s1_1">Sleight of Hand</div>
        <div id="t1l_1" class="t s7_1">(Dex)</div>
        <div id="t1m_1" class="t s1_1">Stealth</div>
        <div id="t1n_1" class="t s7_1">(Dex)</div>
        <div id="t1o_1" class="t s1_1">Survival</div>
        <div id="t1p_1" class="t s7_1">(Wis)</div>
        <div id="t1q_1" class="t s5_1">DEATH SAVES</div>
        <div id="t1r_1" class="t s5_1">HIT DICE</div>
        <div id="t1s_1" class="t s4_1">NAME</div>
        <div id="t1t_1" class="t s4_1">ATK BONUS</div>
        <div id="t1u_1" class="t s4_1">DAMAGE/TYPE</div>
        <div id="t1v_1" class="t s3_1">Total</div>
        <div id="t1w_1" class="t s5_1">SUCCESSES</div>
        <div id="t1x_1" class="t s5_1">FAILURES</div>
        <div id="t1y_1" class="t s5_1">IDEALS</div>
        <div id="t1z_1" class="t s5_1">BONDS</div>
        <div id="t20_1" class="t s5_1">FLAWS</div>
        <div id="t21_1" class="t s5_1">PERSONALITY TRAITS</div>
        <div id="t22_1" class="t s6_1">ARMOR</div>
        <div id="t23_1" class="t s6_1">CLASS</div>
        <div id="t24_1" class="t s6_1">CURRENT HIT POINTS</div>
        <div id="t25_1" class="t s6_1">TEMPORARY HIT POINTS</div>
        <div id="t26_1" class="t s6_1">INITIATIVE</div>
        <div id="t27_1" class="t s6_1">SPEED</div>
        <div id="t28_1" class="t s8_1">PROFICIENCY BONUS</div>
        <div id="t29_1" class="t s6_1">STRENGTH</div>
        <div id="t2a_1" class="t s6_1">DEXTERITY</div>
        <div id="t2b_1" class="t s6_1">CONSTITUTION</div>
        <div id="t2c_1" class="t s6_1">INTELLIGENCE</div>
        <div id="t2d_1" class="t s6_1">WISDOM</div>
        <div id="t2e_1" class="t s6_1">CHARISMA</div>
        <div id="t2f_1" class="t s6_1">SAVING THROWS</div>
        <div id="t2g_1" class="t s8_1">INSPIRATION</div>
        <div id="t2h_1" class="t s6_1">SKILLS</div>

        <!-- End text definitions -->

        <!-- Begin Form Data -->
        <form id="important_form">
          <input
            class="i"
            id="form1_1"
            type="checkbox"
            tabindex="84"
            data-objref="2646 0 R"
            data-field-name="Check Box 36"
            imageName="svg/form/1st_page_form/2646 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form2_1"
            type="checkbox"
            tabindex="74"
            data-objref="2626 0 R"
            data-field-name="Check Box 26"
            imageName="svg/form/1st_page_form/2626 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form3_1"
            type="checkbox"
            tabindex="57"
            data-objref="2605 0 R"
            data-field-name="Check Box 22"
            imageName="svg/form/1st_page_form/2605 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form4_1"
            type="checkbox"
            tabindex="86"
            data-objref="2650 0 R"
            data-field-name="Check Box 38"
            imageName="svg/form/1st_page_form/2650 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form5_1"
            type="checkbox"
            tabindex="80"
            data-objref="2638 0 R"
            data-field-name="Check Box 32"
            imageName="svg/form/1st_page_form/2638 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form6_1"
            type="checkbox"
            tabindex="55"
            data-objref="2601 0 R"
            data-field-name="Check Box 20"
            imageName="svg/form/1st_page_form/2601 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form7_1"
            type="checkbox"
            tabindex="82"
            data-objref="2642 0 R"
            data-field-name="Check Box 34"
            imageName="svg/form/1st_page_form/2642 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form8_1"
            type="checkbox"
            tabindex="72"
            data-objref="2622 0 R"
            data-field-name="Check Box 24"
            imageName="svg/form/1st_page_form/2622 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form9_1"
            type="checkbox"
            tabindex="76"
            data-objref="2630 0 R"
            data-field-name="Check Box 28"
            imageName="svg/form/1st_page_form/2630 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form10_1"
            type="checkbox"
            tabindex="56"
            data-objref="2603 0 R"
            data-field-name="Check Box 21"
            imageName="svg/form/1st_page_form/2603 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form11_1"
            type="checkbox"
            tabindex="81"
            data-objref="2640 0 R"
            data-field-name="Check Box 33"
            imageName="svg/form/1st_page_form/2640 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form12_1"
            type="checkbox"
            tabindex="88"
            data-objref="2654 0 R"
            data-field-name="Check Box 40"
            imageName="svg/form/1st_page_form/2654 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form13_1"
            type="checkbox"
            tabindex="77"
            data-objref="2632 0 R"
            data-field-name="Check Box 29"
            imageName="svg/form/1st_page_form/2632 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form14_1"
            type="checkbox"
            tabindex="79"
            data-objref="2636 0 R"
            data-field-name="Check Box 31"
            imageName="svg/form/1st_page_form/2636 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form15_1"
            type="checkbox"
            tabindex="52"
            data-objref="2595 0 R"
            data-field-name="Check Box 11"
            imageName="svg/form/1st_page_form/2595 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form16_1"
            type="checkbox"
            tabindex="83"
            data-objref="2644 0 R"
            data-field-name="Check Box 35"
            imageName="svg/form/1st_page_form/2644 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form17_1"
            type="checkbox"
            tabindex="75"
            data-objref="2628 0 R"
            data-field-name="Check Box 27"
            imageName="svg/form/1st_page_form/2628 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form18_1"
            type="checkbox"
            tabindex="53"
            data-objref="2597 0 R"
            data-field-name="Check Box 18"
            imageName="svg/form/1st_page_form/2597 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form19_1"
            type="checkbox"
            tabindex="71"
            data-objref="2620 0 R"
            data-field-name="Check Box 23"
            imageName="svg/form/1st_page_form/2620 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form20_1"
            type="checkbox"
            tabindex="85"
            data-objref="2648 0 R"
            data-field-name="Check Box 37"
            imageName="svg/form/1st_page_form/2648 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form21_1"
            type="checkbox"
            tabindex="73"
            data-objref="2624 0 R"
            data-field-name="Check Box 25"
            imageName="svg/form/1st_page_form/2624 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form22_1"
            type="checkbox"
            tabindex="54"
            data-objref="2599 0 R"
            data-field-name="Check Box 19"
            imageName="svg/form/1st_page_form/2599 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form23_1"
            type="checkbox"
            tabindex="87"
            data-objref="2652 0 R"
            data-field-name="Check Box 39"
            imageName="svg/form/1st_page_form/2652 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form24_1"
            type="checkbox"
            tabindex="78"
            data-objref="2634 0 R"
            data-field-name="Check Box 30"
            imageName="svg/form/1st_page_form/2634 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form25_1"
            type="checkbox"
            tabindex="29"
            data-objref="2571 0 R"
            data-field-name="Check Box 17"
            imageName="svg/form/1st_page_form/2571 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form26_1"
            type="checkbox"
            tabindex="28"
            data-objref="2569 0 R"
            data-field-name="Check Box 16"
            imageName="svg/form/1st_page_form/2569 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form27_1"
            type="checkbox"
            tabindex="27"
            data-objref="2567 0 R"
            data-field-name="Check Box 15"
            imageName="svg/form/1st_page_form/2567 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form28_1"
            type="checkbox"
            tabindex="25"
            data-objref="2564 0 R"
            data-field-name="Check Box 14"
            imageName="svg/form/1st_page_form/2564 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form29_1"
            type="checkbox"
            tabindex="24"
            data-objref="2562 0 R"
            data-field-name="Check Box 13"
            imageName="svg/form/1st_page_form/2562 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form30_1"
            type="checkbox"
            tabindex="23"
            data-objref="2560 0 R"
            data-field-name="Check Box 12"
            imageName="svg/form/1st_page_form/2560 0 R"
            images="110100"
          />
          <input
            id="form31_1"
            type="text"
            tabindex="60"
            value=""
            data-objref="2609 0 R"
            data-field-name="Investigation "
          />
          <input
            id="form32_1"
            type="text"
            tabindex="70"
            value=""
            data-objref="2619 0 R"
            data-field-name="Stealth "
          />
          <input
            id="form33_1"
            type="text"
            tabindex="69"
            value=""
            data-objref="2618 0 R"
            data-field-name="Religion"
          />
          <input
            id="form34_1"
            type="text"
            tabindex="67"
            value=""
            data-objref="2616 0 R"
            data-field-name="Performance"
          />
          <input
            id="form35_1"
            type="text"
            tabindex="46"
            value=""
            data-objref="2589 0 R"
            data-field-name="Insight"
          />
          <input
            id="form36_1"
            type="text"
            tabindex="41"
            value=""
            data-objref="2584 0 R"
            data-field-name="Deception "
          />
          <input
            id="form37_1"
            type="text"
            tabindex="66"
            value=""
            data-objref="2615 0 R"
            data-field-name="Nature"
          />
          <input
            id="form38_1"
            type="text"
            tabindex="38"
            value=""
            data-objref="2581 0 R"
            data-field-name="Acrobatics"
          />
          <input
            id="form39_1"
            type="text"
            tabindex="36"
            value=""
            data-objref="2579 0 R"
            data-field-name="ST Wisdom"
          />
          <input
            id="form40_1"
            type="text"
            tabindex="62"
            value=""
            data-objref="2611 0 R"
            data-field-name="Arcana"
          />
          <input
            id="form41_1"
            type="text"
            tabindex="34"
            value=""
            data-objref="2577 0 R"
            data-field-name="ST Constitution"
          />
          <input
            id="form42_1"
            type="text"
            tabindex="16"
            value=""
            data-objref="2553 0 R"
            data-field-name="ST Strength"
          />
          <input
            id="form43_1"
            type="text"
            tabindex="63"
            value=""
            data-objref="2612 0 R"
            data-field-name="Perception "
          />
          <input
            id="form44_1"
            type="text"
            tabindex="47"
            value=""
            data-objref="2590 0 R"
            data-field-name="Intimidation"
          />
          <input
            id="form45_1"
            type="text"
            tabindex="89"
            value=""
            data-objref="2656 0 R"
            data-field-name="Persuasion"
          />
          <input
            id="form46_1"
            type="text"
            tabindex="94"
            value=""
            data-objref="2661 0 R"
            data-field-name="SleightofHand"
          />
          <input
            id="form47_1"
            type="text"
            tabindex="96"
            value=""
            data-objref="2663 0 R"
            data-field-name="Survival"
          />
          <input
            id="form48_1"
            type="text"
            tabindex="42"
            value=""
            data-objref="2585 0 R"
            data-field-name="History "
          />
          <input
            id="form49_1"
            type="text"
            tabindex="40"
            value=""
            data-objref="2583 0 R"
            data-field-name="Athletics"
          />
          <input
            id="form50_1"
            type="text"
            tabindex="39"
            value=""
            data-objref="2582 0 R"
            data-field-name="Animal"
          />
          <input
            id="form51_1"
            type="text"
            tabindex="37"
            value=""
            data-objref="2580 0 R"
            data-field-name="ST Charisma"
          />
          <input
            id="form52_1"
            type="text"
            tabindex="35"
            value=""
            data-objref="2578 0 R"
            data-field-name="ST Intelligence"
          />
          <input
            id="form53_1"
            type="text"
            tabindex="68"
            value=""
            data-objref="2617 0 R"
            data-field-name="Medicine"
          />
          <input
            id="form54_1"
            type="text"
            tabindex="33"
            value=""
            data-objref="2576 0 R"
            data-field-name="ST Dexterity"
          />
          <input
            id="form55_1"
            type="text"
            tabindex="95"
            value=""
            data-objref="2662 0 R"
            data-field-name="CHamod"
          />
          <input
            id="form56_1"
            type="text"
            tabindex="15"
            value=""
            data-objref="2552 0 R"
            data-field-name="STRmod"
          />
          <input
            id="form57_1"
            type="text"
            tabindex="58"
            value=""
            data-objref="2607 0 R"
            data-field-name="INTmod"
          />
          <input
            id="form58_1"
            type="text"
            tabindex="26"
            value=""
            data-objref="2566 0 R"
            data-field-name="CONmod"
          />
          <input
            id="form59_1"
            type="text"
            tabindex="19"
            value=""
            data-objref="2556 0 R"
            data-field-name="DEXmod "
          />
          <input
            id="form60_1"
            type="text"
            tabindex="64"
            value=""
            data-objref="2613 0 R"
            data-field-name="WISmod"
          />
          <input
            id="form61_1"
            type="text"
            tabindex="10"
            value=""
            data-objref="2547 0 R"
            data-field-name="ProfBonus"
          />
          <input
            id="form62_1"
            type="text"
            tabindex="8"
            value=""
            data-objref="2545 0 R"
            data-field-name="Inspiration"
          />
          <input
            id="form63_1"
            type="text"
            tabindex="98"
            value=""
            data-objref="2665 0 R"
            data-field-name="Passive"
          />
          <input
            id="form64_1"
            type="text"
            tabindex="44"
            value=""
            data-objref="2587 0 R"
            data-field-name="Wpn1 AtkBonus"
          />
          <input
            id="form65_1"
            type="text"
            tabindex="49"
            value=""
            data-objref="2592 0 R"
            data-field-name="Wpn2 AtkBonus "
          />
          <input
            id="form66_1"
            type="text"
            tabindex="51"
            value=""
            data-objref="2594 0 R"
            data-field-name="Wpn3 AtkBonus  "
          />
          <input
            id="form67_1"
            type="text"
            tabindex="22"
            value=""
            data-objref="2559 0 R"
            data-field-name="HDTotal"
          />
          <input
            id="form68_1"
            type="text"
            tabindex="103"
            value=""
            data-objref="2670 0 R"
            data-field-name="GP"
          />
          <input
            id="form69_1"
            type="text"
            tabindex="104"
            value=""
            data-objref="2671 0 R"
            data-field-name="PP"
          />
          <input
            id="form70_1"
            type="text"
            tabindex="102"
            value=""
            data-objref="2669 0 R"
            data-field-name="EP"
          />
          <input
            id="form71_1"
            type="text"
            tabindex="99"
            value=""
            data-objref="2666 0 R"
            data-field-name="CP"
          />
          <input
            id="form72_1"
            type="text"
            tabindex="101"
            value=""
            data-objref="2668 0 R"
            data-field-name="SP"
          />
          <input
            id="form73_1"
            type="text"
            tabindex="11"
            value=""
            data-objref="2548 0 R"
            data-field-name="AC"
          />
          <input
            id="form74_1"
            type="text"
            tabindex="59"
            value=""
            data-objref="2608 0 R"
            data-field-name="Wpn2 Damage "
          />
          <input
            id="form75_1"
            type="text"
            tabindex="93"
            value=""
            data-objref="2660 0 R"
            data-field-name="Wpn3 Damage "
          />
          <input
            id="form76_1"
            type="text"
            tabindex="45"
            value=""
            data-objref="2588 0 R"
            data-field-name="Wpn1 Damage"
          />
          <input
            id="form77_1"
            type="text"
            tabindex="50"
            value=""
            data-objref="2593 0 R"
            data-field-name="Wpn Name 3"
          />
          <input
            id="form78_1"
            type="text"
            tabindex="48"
            value=""
            data-objref="2591 0 R"
            data-field-name="Wpn Name 2"
          />
          <input
            id="form79_1"
            type="text"
            tabindex="43"
            value=""
            data-objref="2586 0 R"
            data-field-name="Wpn Name"
          />
          <input
            id="form80_1"
            type="text"
            tabindex="90"
            value=""
            data-objref="2657 0 R"
            data-field-name="HPMax"
          />
          <input
            id="form81_1"
            type="text"
            tabindex="61"
            value=""
            data-objref="2610 0 R"
            data-field-name="WIS"
          />
          <input
            id="form82_1"
            type="text"
            tabindex="21"
            value=""
            data-objref="2558 0 R"
            data-field-name="CON"
          />
          <input
            id="form83_1"
            type="text"
            tabindex="9"
            value=""
            data-objref="2546 0 R"
            data-field-name="STR"
          />
          <input
            id="form84_1"
            type="text"
            tabindex="17"
            value=""
            data-objref="2554 0 R"
            data-field-name="DEX"
          />
          <input
            id="form85_1"
            type="text"
            tabindex="65"
            value=""
            data-objref="2614 0 R"
            data-field-name="CHA"
          />
          <input
            id="form86_1"
            type="text"
            tabindex="32"
            value=""
            data-objref="2575 0 R"
            data-field-name="INT"
          />
          <input
            id="form87_1"
            type="text"
            tabindex="13"
            value=""
            data-objref="2550 0 R"
            data-field-name="Speed"
          />
          <input
            id="form88_1"
            type="text"
            tabindex="12"
            value=""
            data-objref="2549 0 R"
            data-field-name="Initiative"
          />
          <input
            id="form89_1"
            type="text"
            tabindex="30"
            value=""
            data-objref="2573 0 R"
            data-field-name="HD"
          />
          <input
            id="form90_1"
            type="text"
            tabindex="2"
            value=""
            data-objref="2539 0 R"
            data-field-name="Background"
          />
          <input
            id="form91_1"
            type="text"
            tabindex="7"
            value=""
            data-objref="2544 0 R"
            data-field-name="XP"
          />
          <input
            id="form92_1"
            type="text"
            tabindex="6"
            value=""
            data-objref="2543 0 R"
            data-field-name="Alignment"
          />
          <input
            id="form93_1"
            type="text"
            tabindex="3"
            value=""
            data-objref="2540 0 R"
            data-field-name="PlayerName"
          />
          <input
            id="form94_1"
            type="text"
            tabindex="1"
            value=""
            data-objref="2538 0 R"
            data-field-name="ClassLevel"
          />
          <input
            id="form95_1"
            type="text"
            tabindex="5"
            value=""
            data-objref="2542 0 R"
            data-field-name="Race "
          />
          <input
            id="form96_1"
            type="text"
            tabindex="4"
            value=""
            data-objref="2541 0 R"
            data-field-name="CharacterName"
          />
          <input
            id="form97_1"
            type="text"
            tabindex="91"
            value=""
            data-objref="2658 0 R"
            data-field-name="HPCurrent"
          />
          <input
            id="form98_1"
            type="text"
            tabindex="92"
            value=""
            data-objref="2659 0 R"
            data-field-name="HPTemp"
          />
          <textarea
            id="form99_1"
            tabindex="31"
            data-objref="2574 0 R"
            data-field-name="Flaws"
          ></textarea>
          <textarea
            id="form100_1"
            tabindex="18"
            data-objref="2555 0 R"
            data-field-name="Ideals"
          ></textarea>
          <textarea
            id="form101_1"
            tabindex="20"
            data-objref="2557 0 R"
            data-field-name="Bonds"
          ></textarea>
          <textarea
            id="form102_1"
            tabindex="14"
            data-objref="2551 0 R"
            data-field-name="PersonalityTraits "
          ></textarea>
          <textarea
            id="form103_1"
            tabindex="97"
            data-objref="2664 0 R"
            data-field-name="AttacksSpellcasting"
          ></textarea>
          <textarea
            id="form104_1"
            tabindex="105"
            data-objref="2672 0 R"
            data-field-name="Equipment"
          ></textarea>
          <textarea
            id="form105_1"
            tabindex="100"
            data-objref="2667 0 R"
            data-field-name="ProficienciesLang"
          ></textarea>
          <textarea
            id="form106_1"
            tabindex="106"
            data-objref="2673 0 R"
            data-field-name="Features and Traits"
          ></textarea>
        </form>
        <!-- End Form Data -->
      </div>

      <!--- Position background pg. 2 -->
      <div id="p2" class="pageArea">
        <!-- Begin page background -->
        <div id="pg2Overlay"></div>
        <div id="pg2">
          <object
            width="935"
            height="1210"
            data="svg\2nd_page.svg"
            type="image/svg+xml"
            id="pdf2"
          ></object>
        </div>
        <!-- End page background -->

        <!-- Begin text definitions (Positioned/styled in CSS) -->
        <div id="t1_2" class="t s1_2">CHARACTER NAME</div>
        <div id="t2_2" class="t s1_2">EYES</div>
        <div id="t3_2" class="t s1_2">AGE</div>
        <div id="t4_2" class="t s1_2">WEIGHT</div>
        <div id="t5_2" class="t s1_2">HEIGHT</div>
        <div id="t6_2" class="t s1_2">HAIR</div>
        <div id="t7_2" class="t s1_2">SKIN</div>
        <div id="t8_2" class="t s2_2">NAME</div>
        <div id="t9_2" class="t s3_2">TREASURE</div>
        <div id="ta_2" class="t s3_2">CHARACTER BACKSTORY</div>
        <div id="tb_2" class="t s3_2">CHARACTER APPEARANCE</div>
        <div id="tc_2" class="t s3_2">ADDITIONAL FEATURES &amp; TRAITS</div>
        <div id="td_2" class="t s3_2">ALLIES &amp; ORGANIZATIONS</div>
        <div id="te_2" class="t s4_2">SYMBOL</div>
        <div id="tf_2" class="t s5_2"></div>

        <!-- End text definitions -->

        <!-- Begin Form Data -->
        <form>
          <input
            id="form1_2"
            type="text"
            tabindex="3"
            value=""
            data-objref="385 0 R"
            data-field-name="Height"
          />
          <input
            id="form2_2"
            type="text"
            tabindex="6"
            value=""
            data-objref="388 0 R"
            data-field-name="Skin"
          />
          <input
            id="form3_2"
            type="text"
            tabindex="7"
            value=""
            data-objref="379 0 R"
            data-field-name="Hair"
          />
          <input
            id="form4_2"
            type="text"
            tabindex="4"
            value=""
            data-objref="386 0 R"
            data-field-name="Weight"
          />
          <input
            id="form5_2"
            type="text"
            tabindex="2"
            value=""
            data-objref="384 0 R"
            data-field-name="Age"
          />
          <input
            id="form6_2"
            type="text"
            tabindex="5"
            value=""
            data-objref="387 0 R"
            data-field-name="Eyes"
          />
          <input
            id="form7_2"
            type="text"
            tabindex="11"
            value=""
            data-objref="383 0 R"
            data-field-name="FactionName"
          />
          <input
            id="form8_2"
            type="text"
            tabindex="1"
            value=""
            data-objref="390 0 R"
            data-field-name="CharacterName 2"
          />
          <textarea
            id="form13_2"
            tabindex="10"
            data-objref="382 0 R"
            data-field-name="Allies"
          ></textarea>
          <textarea
            id="form14_2"
            tabindex="14"
            data-objref="376 0 R"
            data-field-name="Treasure"
          ></textarea>
          <textarea
            id="form15_2"
            tabindex="12"
            data-objref="374 0 R"
            data-field-name="Backstory"
          ></textarea>
          <textarea
            id="form16_2"
            tabindex="13"
            data-objref="375 0 R"
            data-field-name="Feat+Traits"
          ></textarea>
        </form>
        <!-- End Form Data -->
      </div>

      <!--- Position background pg. 3-->
      <div id="p3" class="pageArea">
        <!-- Begin page background -->
        <div id="pg3Overlay"></div>
        <div id="pg3">
          <object
            width="935"
            height="1210"
            data="svg\3rd_page.svg"
            type="image/svg+xml"
            id="pdf3"
          ></object>
        </div>
        <!-- End page background -->

        <!-- Begin text definitions (Positioned/styled in CSS) -->
        <div id="t1_3" class="t s1_3"></div>
        <div id="t2_3" class="t s2_3">SPELLCASTING</div>
        <div id="t3_3" class="t s2_3">CLASS</div>
        <div id="t4_3" class="t m1_3 s3_3">SPELLS KNOWN</div>
        <div id="t5_3" class="t s4_3">SPELL NAME</div>
        <div id="t6_3" class="t m2_3 s5_3">P</div>
        <div id="t7_3" class="t m3_3 s5_3">R</div>
        <div id="t8_3" class="t m4_3 s5_3">E</div>
        <div id="t9_3" class="t m5_3 s5_3">P</div>
        <div id="ta_3" class="t m6_3 s5_3">A</div>
        <div id="tb_3" class="t m7_3 s5_3">R</div>
        <div id="tc_3" class="t m8_3 s5_3">E</div>
        <div id="td_3" class="t m9_3 s5_3">D</div>
        <div id="te_3" class="t s3_3">SPELL</div>
        <div id="tf_3" class="t s3_3">LEVEL</div>
        <div id="tg_3" class="t s3_3">SLOTS EXPENDED</div>
        <div id="th_3" class="t s3_3">SLOTS TOTAL</div>
        <div id="ti_3" class="t s6_3">1</div>
        <div id="tj_3" class="t s6_3">0</div>
        <div id="tk_3" class="t s6_3">2</div>
        <div id="tl_3" class="t s6_3">3</div>
        <div id="tm_3" class="t s6_3">6</div>
        <div id="tn_3" class="t s6_3">7</div>
        <div id="to_3" class="t s6_3">8</div>
        <div id="tp_3" class="t s6_3">9</div>
        <div id="tq_3" class="t s6_3">4</div>
        <div id="tr_3" class="t s6_3">5</div>
        <div id="ts_3" class="t s2_3">SPELLCASTING</div>
        <div id="tt_3" class="t s2_3">ABILITY</div>
        <div id="tu_3" class="t s2_3">SPELL SAVE DC</div>
        <div id="tv_3" class="t s2_3">SPELL ATTACK</div>
        <div id="tw_3" class="t s2_3">BONUS</div>
        <div id="tx_3" class="t s7_3">CANTRIPS</div>

        <!-- End text definitions -->

        <!-- Begin Form Data -->
        <form>
          <input
            class="i"
            id="form1_3"
            type="checkbox"
            tabindex="173"
            data-objref="688 0 R"
            data-field-name="Check Box 3077"
            imageName="svg/form/3rd_page_form/688 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form2_3"
            type="checkbox"
            tabindex="170"
            data-objref="762 0 R"
            data-field-name="Check Box 3074"
            imageName="svg/form/3rd_page_form/762 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form3_3"
            type="checkbox"
            tabindex="212"
            data-objref="658 0 R"
            data-field-name="Check Box 3082"
            imageName="svg/form/3rd_page_form/658 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form4_3"
            type="checkbox"
            tabindex="210"
            data-objref="670 0 R"
            data-field-name="Check Box 3080"
            imageName="svg/form/3rd_page_form/670 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form5_3"
            type="checkbox"
            tabindex="168"
            data-objref="752 0 R"
            data-field-name="Check Box 325"
            imageName="svg/form/3rd_page_form/752 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form6_3"
            type="checkbox"
            tabindex="209"
            data-objref="674 0 R"
            data-field-name="Check Box 3079"
            imageName="svg/form/3rd_page_form/674 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form7_3"
            type="checkbox"
            tabindex="214"
            data-objref="611 0 R"
            data-field-name="Check Box 3083"
            imageName="svg/form/3rd_page_form/611 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form8_3"
            type="checkbox"
            tabindex="172"
            data-objref="700 0 R"
            data-field-name="Check Box 3076"
            imageName="svg/form/3rd_page_form/700 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form9_3"
            type="checkbox"
            tabindex="169"
            data-objref="732 0 R"
            data-field-name="Check Box 324"
            imageName="svg/form/3rd_page_form/732 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form10_3"
            type="checkbox"
            tabindex="207"
            data-objref="676 0 R"
            data-field-name="Check Box 327"
            imageName="svg/form/3rd_page_form/676 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form11_3"
            type="checkbox"
            tabindex="176"
            data-objref="667 0 R"
            data-field-name="Check Box 3078"
            imageName="svg/form/3rd_page_form/667 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form12_3"
            type="checkbox"
            tabindex="171"
            data-objref="763 0 R"
            data-field-name="Check Box 3075"
            imageName="svg/form/3rd_page_form/763 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form13_3"
            type="checkbox"
            tabindex="211"
            data-objref="659 0 R"
            data-field-name="Check Box 3081"
            imageName="svg/form/3rd_page_form/659 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form14_3"
            type="checkbox"
            tabindex="208"
            data-objref="675 0 R"
            data-field-name="Check Box 326"
            imageName="svg/form/3rd_page_form/675 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form15_3"
            type="checkbox"
            tabindex="102"
            data-objref="815 0 R"
            data-field-name="Check Box 3069"
            imageName="svg/form/3rd_page_form/815 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form16_3"
            type="checkbox"
            tabindex="35"
            data-objref="592 0 R"
            data-field-name="Check Box 3066"
            imageName="svg/form/3rd_page_form/592 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form17_3"
            type="checkbox"
            tabindex="27"
            data-objref="696 0 R"
            data-field-name="Check Box 321"
            imageName="svg/form/3rd_page_form/696 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form18_3"
            type="checkbox"
            tabindex="98"
            data-objref="733 0 R"
            data-field-name="Check Box 323"
            imageName="svg/form/3rd_page_form/733 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form19_3"
            type="checkbox"
            tabindex="99"
            data-objref="734 0 R"
            data-field-name="Check Box 322"
            imageName="svg/form/3rd_page_form/734 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form20_3"
            type="checkbox"
            tabindex="34"
            data-objref="609 0 R"
            data-field-name="Check Box 3065"
            imageName="svg/form/3rd_page_form/609 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form21_3"
            type="checkbox"
            tabindex="28"
            data-objref="694 0 R"
            data-field-name="Check Box 320"
            imageName="svg/form/3rd_page_form/694 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form22_3"
            type="checkbox"
            tabindex="106"
            data-objref="687 0 R"
            data-field-name="Check Box 3073"
            imageName="svg/form/3rd_page_form/687 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form23_3"
            type="checkbox"
            tabindex="100"
            data-objref="797 0 R"
            data-field-name="Check Box 3067"
            imageName="svg/form/3rd_page_form/797 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form24_3"
            type="checkbox"
            tabindex="31"
            data-objref="692 0 R"
            data-field-name="Check Box 3062"
            imageName="svg/form/3rd_page_form/692 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form25_3"
            type="checkbox"
            tabindex="103"
            data-objref="720 0 R"
            data-field-name="Check Box 3070"
            imageName="svg/form/3rd_page_form/720 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form26_3"
            type="checkbox"
            tabindex="30"
            data-objref="691 0 R"
            data-field-name="Check Box 3061"
            imageName="svg/form/3rd_page_form/691 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form27_3"
            type="checkbox"
            tabindex="105"
            data-objref="702 0 R"
            data-field-name="Check Box 3072"
            imageName="svg/form/3rd_page_form/702 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form28_3"
            type="checkbox"
            tabindex="32"
            data-objref="660 0 R"
            data-field-name="Check Box 3063"
            imageName="svg/form/3rd_page_form/660 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form29_3"
            type="checkbox"
            tabindex="29"
            data-objref="693 0 R"
            data-field-name="Check Box 3060"
            imageName="svg/form/3rd_page_form/693 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form30_3"
            type="checkbox"
            tabindex="101"
            data-objref="814 0 R"
            data-field-name="Check Box 3068"
            imageName="svg/form/3rd_page_form/814 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form31_3"
            type="checkbox"
            tabindex="104"
            data-objref="701 0 R"
            data-field-name="Check Box 3071"
            imageName="svg/form/3rd_page_form/701 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form32_3"
            type="checkbox"
            tabindex="33"
            data-objref="622 0 R"
            data-field-name="Check Box 3064"
            imageName="svg/form/3rd_page_form/622 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form33_3"
            type="checkbox"
            tabindex="201"
            data-objref="750 0 R"
            data-field-name="Check Box 3054"
            imageName="svg/form/3rd_page_form/750 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form34_3"
            type="checkbox"
            tabindex="206"
            data-objref="695 0 R"
            data-field-name="Check Box 3059"
            imageName="svg/form/3rd_page_form/695 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form35_3"
            type="checkbox"
            tabindex="190"
            data-objref="455 0 R"
            data-field-name="Check Box 3029"
            imageName="svg/form/3rd_page_form/455 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form36_3"
            type="checkbox"
            tabindex="184"
            data-objref="627 0 R"
            data-field-name="Check Box 3023"
            imageName="svg/form/3rd_page_form/627 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form37_3"
            type="checkbox"
            tabindex="204"
            data-objref="813 0 R"
            data-field-name="Check Box 3057"
            imageName="svg/form/3rd_page_form/813 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form38_3"
            type="checkbox"
            tabindex="182"
            data-objref="628 0 R"
            data-field-name="Check Box 3021"
            imageName="svg/form/3rd_page_form/628 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form39_3"
            type="checkbox"
            tabindex="198"
            data-objref="405 0 R"
            data-field-name="Check Box 319"
            imageName="svg/form/3rd_page_form/405 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form40_3"
            type="checkbox"
            tabindex="179"
            data-objref="652 0 R"
            data-field-name="Check Box 313"
            imageName="svg/form/3rd_page_form/652 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form41_3"
            type="checkbox"
            tabindex="188"
            data-objref="478 0 R"
            data-field-name="Check Box 3027"
            imageName="svg/form/3rd_page_form/478 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form42_3"
            type="checkbox"
            tabindex="203"
            data-objref="790 0 R"
            data-field-name="Check Box 3056"
            imageName="svg/form/3rd_page_form/790 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form43_3"
            type="checkbox"
            tabindex="200"
            data-objref="749 0 R"
            data-field-name="Check Box 3053"
            imageName="svg/form/3rd_page_form/749 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form44_3"
            type="checkbox"
            tabindex="183"
            data-objref="629 0 R"
            data-field-name="Check Box 3022"
            imageName="svg/form/3rd_page_form/629 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form45_3"
            type="checkbox"
            tabindex="180"
            data-objref="653 0 R"
            data-field-name="Check Box 310"
            imageName="svg/form/3rd_page_form/653 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form46_3"
            type="checkbox"
            tabindex="189"
            data-objref="479 0 R"
            data-field-name="Check Box 3028"
            imageName="svg/form/3rd_page_form/479 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form47_3"
            type="checkbox"
            tabindex="166"
            data-objref="419 0 R"
            data-field-name="Check Box 3052"
            imageName="svg/form/3rd_page_form/419 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form48_3"
            type="checkbox"
            tabindex="199"
            data-objref="400 0 R"
            data-field-name="Check Box 318"
            imageName="svg/form/3rd_page_form/400 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form49_3"
            type="checkbox"
            tabindex="181"
            data-objref="647 0 R"
            data-field-name="Check Box 3020"
            imageName="svg/form/3rd_page_form/647 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form50_3"
            type="checkbox"
            tabindex="185"
            data-objref="590 0 R"
            data-field-name="Check Box 3024"
            imageName="svg/form/3rd_page_form/590 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form51_3"
            type="checkbox"
            tabindex="205"
            data-objref="703 0 R"
            data-field-name="Check Box 3058"
            imageName="svg/form/3rd_page_form/703 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form52_3"
            type="checkbox"
            tabindex="191"
            data-objref="425 0 R"
            data-field-name="Check Box 3030"
            imageName="svg/form/3rd_page_form/425 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form53_3"
            type="checkbox"
            tabindex="187"
            data-objref="546 0 R"
            data-field-name="Check Box 3026"
            imageName="svg/form/3rd_page_form/546 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form54_3"
            type="checkbox"
            tabindex="186"
            data-objref="591 0 R"
            data-field-name="Check Box 3025"
            imageName="svg/form/3rd_page_form/591 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form55_3"
            type="checkbox"
            tabindex="202"
            data-objref="764 0 R"
            data-field-name="Check Box 3055"
            imageName="svg/form/3rd_page_form/764 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form56_3"
            type="checkbox"
            tabindex="114"
            data-objref="672 0 R"
            data-field-name="Check Box 317"
            imageName="svg/form/3rd_page_form/672 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form57_3"
            type="checkbox"
            tabindex="26"
            data-objref="698 0 R"
            data-field-name="Check Box 3040"
            imageName="svg/form/3rd_page_form/698 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form58_3"
            type="checkbox"
            tabindex="25"
            data-objref="697 0 R"
            data-field-name="Check Box 3039"
            imageName="svg/form/3rd_page_form/697 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form59_3"
            type="checkbox"
            tabindex="50"
            data-objref="399 0 R"
            data-field-name="Check Box 3019"
            imageName="svg/form/3rd_page_form/399 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form60_3"
            type="checkbox"
            tabindex="24"
            data-objref="699 0 R"
            data-field-name="Check Box 3038"
            imageName="svg/form/3rd_page_form/699 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form61_3"
            type="checkbox"
            tabindex="47"
            data-objref="456 0 R"
            data-field-name="Check Box 3016"
            imageName="svg/form/3rd_page_form/456 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form62_3"
            type="checkbox"
            tabindex="18"
            data-objref="759 0 R"
            data-field-name="Check Box 3032"
            imageName="svg/form/3rd_page_form/759 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form63_3"
            type="checkbox"
            tabindex="23"
            data-objref="721 0 R"
            data-field-name="Check Box 3037"
            imageName="svg/form/3rd_page_form/721 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form64_3"
            type="checkbox"
            tabindex="46"
            data-objref="476 0 R"
            data-field-name="Check Box 3015"
            imageName="svg/form/3rd_page_form/476 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form65_3"
            type="checkbox"
            tabindex="45"
            data-objref="498 0 R"
            data-field-name="Check Box 3014"
            imageName="svg/form/3rd_page_form/498 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form66_3"
            type="checkbox"
            tabindex="22"
            data-objref="709 0 R"
            data-field-name="Check Box 3036"
            imageName="svg/form/3rd_page_form/709 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form67_3"
            type="checkbox"
            tabindex="43"
            data-objref="528 0 R"
            data-field-name="Check Box 3012"
            imageName="svg/form/3rd_page_form/528 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form68_3"
            type="checkbox"
            tabindex="42"
            data-objref="527 0 R"
            data-field-name="Check Box 3011"
            imageName="svg/form/3rd_page_form/527 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form69_3"
            type="checkbox"
            tabindex="156"
            data-objref="521 0 R"
            data-field-name="Check Box 3042"
            imageName="svg/form/3rd_page_form/521 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form70_3"
            type="checkbox"
            tabindex="157"
            data-objref="522 0 R"
            data-field-name="Check Box 3043"
            imageName="svg/form/3rd_page_form/522 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form71_3"
            type="checkbox"
            tabindex="20"
            data-objref="761 0 R"
            data-field-name="Check Box 3034"
            imageName="svg/form/3rd_page_form/761 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form72_3"
            type="checkbox"
            tabindex="160"
            data-objref="467 0 R"
            data-field-name="Check Box 3046"
            imageName="svg/form/3rd_page_form/467 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form73_3"
            type="checkbox"
            tabindex="40"
            data-objref="575 0 R"
            data-field-name="Check Box 309"
            imageName="svg/form/3rd_page_form/575 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form74_3"
            type="checkbox"
            tabindex="161"
            data-objref="439 0 R"
            data-field-name="Check Box 3047"
            imageName="svg/form/3rd_page_form/439 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form75_3"
            type="checkbox"
            tabindex="163"
            data-objref="434 0 R"
            data-field-name="Check Box 3049"
            imageName="svg/form/3rd_page_form/434 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form76_3"
            type="checkbox"
            tabindex="39"
            data-objref="578 0 R"
            data-field-name="Check Box 251"
            imageName="svg/form/3rd_page_form/578 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form77_3"
            type="checkbox"
            tabindex="162"
            data-objref="433 0 R"
            data-field-name="Check Box 3048"
            imageName="svg/form/3rd_page_form/433 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form78_3"
            type="checkbox"
            tabindex="19"
            data-objref="722 0 R"
            data-field-name="Check Box 3033"
            imageName="svg/form/3rd_page_form/722 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form79_3"
            type="checkbox"
            tabindex="159"
            data-objref="466 0 R"
            data-field-name="Check Box 3045"
            imageName="svg/form/3rd_page_form/466 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form80_3"
            type="checkbox"
            tabindex="37"
            data-objref="589 0 R"
            data-field-name="Check Box 3041"
            imageName="svg/form/3rd_page_form/589 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form81_3"
            type="checkbox"
            tabindex="41"
            data-objref="553 0 R"
            data-field-name="Check Box 3010"
            imageName="svg/form/3rd_page_form/553 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form82_3"
            type="checkbox"
            tabindex="158"
            data-objref="477 0 R"
            data-field-name="Check Box 3044"
            imageName="svg/form/3rd_page_form/477 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form83_3"
            type="checkbox"
            tabindex="165"
            data-objref="418 0 R"
            data-field-name="Check Box 3051"
            imageName="svg/form/3rd_page_form/418 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form84_3"
            type="checkbox"
            tabindex="21"
            data-objref="719 0 R"
            data-field-name="Check Box 3035"
            imageName="svg/form/3rd_page_form/719 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form85_3"
            type="checkbox"
            tabindex="164"
            data-objref="428 0 R"
            data-field-name="Check Box 3050"
            imageName="svg/form/3rd_page_form/428 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form86_3"
            type="checkbox"
            tabindex="155"
            data-objref="557 0 R"
            data-field-name="Check Box 316"
            imageName="svg/form/3rd_page_form/557 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form87_3"
            type="checkbox"
            tabindex="36"
            data-objref="593 0 R"
            data-field-name="Check Box 315"
            imageName="svg/form/3rd_page_form/593 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form88_3"
            type="checkbox"
            tabindex="44"
            data-objref="499 0 R"
            data-field-name="Check Box 3013"
            imageName="svg/form/3rd_page_form/499 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form89_3"
            type="checkbox"
            tabindex="17"
            data-objref="760 0 R"
            data-field-name="Check Box 3031"
            imageName="svg/form/3rd_page_form/760 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form90_3"
            type="checkbox"
            tabindex="49"
            data-objref="440 0 R"
            data-field-name="Check Box 3018"
            imageName="svg/form/3rd_page_form/440 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form91_3"
            type="checkbox"
            tabindex="16"
            data-objref="744 0 R"
            data-field-name="Check Box 314"
            imageName="svg/form/3rd_page_form/744 0 R"
            images="110100"
          />
          <input
            class="i"
            id="form92_3"
            type="checkbox"
            tabindex="48"
            data-objref="457 0 R"
            data-field-name="Check Box 3017"
            imageName="svg/form/3rd_page_form/457 0 R"
            images="110100"
          />
          <input
            id="form93_3"
            type="text"
            tabindex="91"
            value=""
            data-objref="773 0 R"
            data-field-name="SlotsTotal 22"
          />
          <input
            id="form94_3"
            type="text"
            tabindex="61"
            value=""
            data-objref="779 0 R"
            data-field-name="SlotsTotal 20"
          />
          <input
            id="form95_3"
            type="text"
            tabindex="116"
            value=""
            data-objref="661 0 R"
            data-field-name="SlotsTotal 23"
          />
          <input
            id="form96_3"
            type="text"
            tabindex="127"
            value=""
            data-objref="641 0 R"
            data-field-name="SlotsTotal 24"
          />
          <input
            id="form97_3"
            type="text"
            tabindex="5"
            value=""
            data-objref="810 0 R"
            data-field-name="SlotsTotal 19"
          />
          <input
            id="form98_3"
            type="text"
            tabindex="177"
            value=""
            data-objref="668 0 R"
            data-field-name="SlotsTotal 27"
          />
          <input
            id="form99_3"
            type="text"
            tabindex="76"
            value=""
            data-objref="793 0 R"
            data-field-name="SlotsTotal 21"
          />
          <input
            id="form100_3"
            type="text"
            tabindex="149"
            value=""
            data-objref="586 0 R"
            data-field-name="SlotsTotal 26"
          />
          <input
            id="form101_3"
            type="text"
            tabindex="138"
            value=""
            data-objref="620 0 R"
            data-field-name="SlotsTotal 25"
          />
          <input
            id="form102_3"
            type="text"
            tabindex="115"
            value=""
            data-objref="673 0 R"
            data-field-name="Spells 1072"
          />
          <input
            id="form103_3"
            type="text"
            tabindex="113"
            value=""
            data-objref="671 0 R"
            data-field-name="Spells 1071"
          />
          <input
            id="form104_3"
            type="text"
            tabindex="112"
            value=""
            data-objref="686 0 R"
            data-field-name="Spells 1070"
          />
          <input
            id="form105_3"
            type="text"
            tabindex="111"
            value=""
            data-objref="685 0 R"
            data-field-name="Spells 1069"
          />
          <input
            id="form106_3"
            type="text"
            tabindex="109"
            value=""
            data-objref="683 0 R"
            data-field-name="Spells 1067"
          />
          <input
            id="form107_3"
            type="text"
            tabindex="107"
            value=""
            data-objref="681 0 R"
            data-field-name="Spells 1065"
          />
          <input
            id="form108_3"
            type="text"
            tabindex="97"
            value=""
            data-objref="826 0 R"
            data-field-name="Spells 1064"
          />
          <input
            id="form109_3"
            type="text"
            tabindex="96"
            value=""
            data-objref="825 0 R"
            data-field-name="Spells 1063"
          />
          <input
            id="form110_3"
            type="text"
            tabindex="94"
            value=""
            data-objref="823 0 R"
            data-field-name="Spells 1061"
          />
          <input
            id="form111_3"
            type="text"
            tabindex="167"
            value=""
            data-objref="751 0 R"
            data-field-name="Spells 10104"
          />
          <input
            id="form112_3"
            type="text"
            tabindex="154"
            value=""
            data-objref="556 0 R"
            data-field-name="Spells 10103"
          />
          <input
            id="form113_3"
            type="text"
            tabindex="153"
            value=""
            data-objref="555 0 R"
            data-field-name="Spells 10102"
          />
          <input
            id="form114_3"
            type="text"
            tabindex="152"
            value=""
            data-objref="554 0 R"
            data-field-name="Spells 10101"
          />
          <input
            id="form115_3"
            type="text"
            tabindex="63"
            value=""
            data-objref="785 0 R"
            data-field-name="Spells 1034"
          />
          <input
            id="form116_3"
            type="text"
            tabindex="125"
            value=""
            data-objref="639 0 R"
            data-field-name="Spells 1080"
          />
          <input
            id="form117_3"
            type="text"
            tabindex="124"
            value=""
            data-objref="638 0 R"
            data-field-name="Spells 1079"
          />
          <input
            id="form118_3"
            type="text"
            tabindex="123"
            value=""
            data-objref="637 0 R"
            data-field-name="Spells 1078"
          />
          <input
            id="form119_3"
            type="text"
            tabindex="122"
            value=""
            data-objref="636 0 R"
            data-field-name="Spells 1077"
          />
          <input
            id="form120_3"
            type="text"
            tabindex="121"
            value=""
            data-objref="666 0 R"
            data-field-name="Spells 1076"
          />
          <input
            id="form121_3"
            type="text"
            tabindex="120"
            value=""
            data-objref="665 0 R"
            data-field-name="Spells 1075"
          />
          <input
            id="form122_3"
            type="text"
            tabindex="60"
            value=""
            data-objref="778 0 R"
            data-field-name="Spells 1033"
          />
          <input
            id="form123_3"
            type="text"
            tabindex="119"
            value=""
            data-objref="664 0 R"
            data-field-name="Spells 1074"
          />
          <input
            id="form124_3"
            type="text"
            tabindex="118"
            value=""
            data-objref="663 0 R"
            data-field-name="Spells 1073"
          />
          <input
            id="form125_3"
            type="text"
            tabindex="126"
            value=""
            data-objref="640 0 R"
            data-field-name="Spells 1081"
          />
          <input
            id="form126_3"
            type="text"
            tabindex="59"
            value=""
            data-objref="777 0 R"
            data-field-name="Spells 1032"
          />
          <input
            id="form127_3"
            type="text"
            tabindex="58"
            value=""
            data-objref="776 0 R"
            data-field-name="Spells 1031"
          />
          <input
            id="form128_3"
            type="text"
            tabindex="57"
            value=""
            data-objref="775 0 R"
            data-field-name="Spells 1030"
          />
          <input
            id="form129_3"
            type="text"
            tabindex="56"
            value=""
            data-objref="774 0 R"
            data-field-name="Spells 1029"
          />
          <input
            id="form130_3"
            type="text"
            tabindex="110"
            value=""
            data-objref="684 0 R"
            data-field-name="Spells 1068"
          />
          <input
            id="form131_3"
            type="text"
            tabindex="55"
            value=""
            data-objref="757 0 R"
            data-field-name="Spells 1028"
          />
          <input
            id="form132_3"
            type="text"
            tabindex="108"
            value=""
            data-objref="682 0 R"
            data-field-name="Spells 1066"
          />
          <input
            id="form133_3"
            type="text"
            tabindex="54"
            value=""
            data-objref="756 0 R"
            data-field-name="Spells 1027"
          />
          <input
            id="form134_3"
            type="text"
            tabindex="213"
            value=""
            data-objref="610 0 R"
            data-field-name="Spells 101013"
          />
          <input
            id="form135_3"
            type="text"
            tabindex="53"
            value=""
            data-objref="755 0 R"
            data-field-name="Spells 1026"
          />
          <input
            id="form136_3"
            type="text"
            tabindex="52"
            value=""
            data-objref="754 0 R"
            data-field-name="Spells 1025"
          />
          <input
            id="form137_3"
            type="text"
            tabindex="51"
            value=""
            data-objref="753 0 R"
            data-field-name="Spells 1024"
          />
          <input
            id="form138_3"
            type="text"
            tabindex="197"
            value=""
            data-objref="404 0 R"
            data-field-name="Spells 101012"
          />
          <input
            id="form139_3"
            type="text"
            tabindex="196"
            value=""
            data-objref="403 0 R"
            data-field-name="Spells 101011"
          />
          <input
            id="form140_3"
            type="text"
            tabindex="195"
            value=""
            data-objref="402 0 R"
            data-field-name="Spells 101010"
          />
          <input
            id="form141_3"
            type="text"
            tabindex="194"
            value=""
            data-objref="401 0 R"
            data-field-name="Spells 10109"
          />
          <input
            id="form142_3"
            type="text"
            tabindex="193"
            value=""
            data-objref="427 0 R"
            data-field-name="Spells 10108"
          />
          <input
            id="form143_3"
            type="text"
            tabindex="192"
            value=""
            data-objref="426 0 R"
            data-field-name="Spells 10107"
          />
          <input
            id="form144_3"
            type="text"
            tabindex="95"
            value=""
            data-objref="824 0 R"
            data-field-name="Spells 1062"
          />
          <input
            id="form145_3"
            type="text"
            tabindex="93"
            value=""
            data-objref="822 0 R"
            data-field-name="Spells 1060"
          />
          <input
            id="form146_3"
            type="text"
            tabindex="90"
            value=""
            data-objref="772 0 R"
            data-field-name="Spells 1059"
          />
          <input
            id="form147_3"
            type="text"
            tabindex="89"
            value=""
            data-objref="771 0 R"
            data-field-name="Spells 1058"
          />
          <input
            id="form148_3"
            type="text"
            tabindex="88"
            value=""
            data-objref="770 0 R"
            data-field-name="Spells 1057"
          />
          <input
            id="form149_3"
            type="text"
            tabindex="87"
            value=""
            data-objref="769 0 R"
            data-field-name="Spells 1056"
          />
          <input
            id="form150_3"
            type="text"
            tabindex="86"
            value=""
            data-objref="768 0 R"
            data-field-name="Spells 1055"
          />
          <input
            id="form151_3"
            type="text"
            tabindex="85"
            value=""
            data-objref="807 0 R"
            data-field-name="Spells 1054"
          />
          <input
            id="form152_3"
            type="text"
            tabindex="84"
            value=""
            data-objref="806 0 R"
            data-field-name="Spells 1053"
          />
          <input
            id="form153_3"
            type="text"
            tabindex="83"
            value=""
            data-objref="805 0 R"
            data-field-name="Spells 1052"
          />
          <input
            id="form154_3"
            type="text"
            tabindex="82"
            value=""
            data-objref="804 0 R"
            data-field-name="Spells 1051"
          />
          <input
            id="form155_3"
            type="text"
            tabindex="81"
            value=""
            data-objref="803 0 R"
            data-field-name="Spells 1050"
          />
          <input
            id="form156_3"
            type="text"
            tabindex="80"
            value=""
            data-objref="802 0 R"
            data-field-name="Spells 1049"
          />
          <input
            id="form157_3"
            type="text"
            tabindex="79"
            value=""
            data-objref="796 0 R"
            data-field-name="Spells 1048"
          />
          <input
            id="form158_3"
            type="text"
            tabindex="78"
            value=""
            data-objref="795 0 R"
            data-field-name="Spells 1047"
          />
          <input
            id="form159_3"
            type="text"
            tabindex="38"
            value=""
            data-objref="577 0 R"
            data-field-name="Spells 1023"
          />
          <input
            id="form160_3"
            type="text"
            tabindex="151"
            value=""
            data-objref="588 0 R"
            data-field-name="Spells 10100"
          />
          <input
            id="form161_3"
            type="text"
            tabindex="75"
            value=""
            data-objref="792 0 R"
            data-field-name="Spells 1046"
          />
          <input
            id="form162_3"
            type="text"
            tabindex="148"
            value=""
            data-objref="585 0 R"
            data-field-name="Spells 1099"
          />
          <input
            id="form163_3"
            type="text"
            tabindex="74"
            value=""
            data-objref="791 0 R"
            data-field-name="Spells 1045"
          />
          <input
            id="form164_3"
            type="text"
            tabindex="147"
            value=""
            data-objref="584 0 R"
            data-field-name="Spells 1098"
          />
          <input
            id="form165_3"
            type="text"
            tabindex="146"
            value=""
            data-objref="583 0 R"
            data-field-name="Spells 1097"
          />
          <input
            id="form166_3"
            type="text"
            tabindex="73"
            value=""
            data-objref="740 0 R"
            data-field-name="Spells 1044"
          />
          <input
            id="form167_3"
            type="text"
            tabindex="145"
            value=""
            data-objref="608 0 R"
            data-field-name="Spells 1096"
          />
          <input
            id="form168_3"
            type="text"
            tabindex="144"
            value=""
            data-objref="607 0 R"
            data-field-name="Spells 1095"
          />
          <input
            id="form169_3"
            type="text"
            tabindex="72"
            value=""
            data-objref="739 0 R"
            data-field-name="Spells 1043"
          />
          <input
            id="form170_3"
            type="text"
            tabindex="143"
            value=""
            data-objref="606 0 R"
            data-field-name="Spells 1094"
          />
          <input
            id="form171_3"
            type="text"
            tabindex="142"
            value=""
            data-objref="605 0 R"
            data-field-name="Spells 1093"
          />
          <input
            id="form172_3"
            type="text"
            tabindex="71"
            value=""
            data-objref="738 0 R"
            data-field-name="Spells 1042"
          />
          <input
            id="form173_3"
            type="text"
            tabindex="174"
            value=""
            data-objref="689 0 R"
            data-field-name="Spells 10105"
          />
          <input
            id="form174_3"
            type="text"
            tabindex="175"
            value=""
            data-objref="690 0 R"
            data-field-name="Spells 10106"
          />
          <input
            id="form175_3"
            type="text"
            tabindex="141"
            value=""
            data-objref="604 0 R"
            data-field-name="Spells 1092"
          />
          <input
            id="form176_3"
            type="text"
            tabindex="140"
            value=""
            data-objref="603 0 R"
            data-field-name="Spells 1091"
          />
          <input
            id="form177_3"
            type="text"
            tabindex="70"
            value=""
            data-objref="737 0 R"
            data-field-name="Spells 1041"
          />
          <input
            id="form178_3"
            type="text"
            tabindex="69"
            value=""
            data-objref="736 0 R"
            data-field-name="Spells 1040"
          />
          <input
            id="form179_3"
            type="text"
            tabindex="137"
            value=""
            data-objref="619 0 R"
            data-field-name="Spells 1090"
          />
          <input
            id="form180_3"
            type="text"
            tabindex="136"
            value=""
            data-objref="618 0 R"
            data-field-name="Spells 1089"
          />
          <input
            id="form181_3"
            type="text"
            tabindex="68"
            value=""
            data-objref="735 0 R"
            data-field-name="Spells 1039"
          />
          <input
            id="form182_3"
            type="text"
            tabindex="135"
            value=""
            data-objref="617 0 R"
            data-field-name="Spells 1088"
          />
          <input
            id="form183_3"
            type="text"
            tabindex="134"
            value=""
            data-objref="616 0 R"
            data-field-name="Spells 1087"
          />
          <input
            id="form184_3"
            type="text"
            tabindex="67"
            value=""
            data-objref="789 0 R"
            data-field-name="Spells 1038"
          />
          <input
            id="form185_3"
            type="text"
            tabindex="133"
            value=""
            data-objref="635 0 R"
            data-field-name="Spells 1086"
          />
          <input
            id="form186_3"
            type="text"
            tabindex="132"
            value=""
            data-objref="634 0 R"
            data-field-name="Spells 1085"
          />
          <input
            id="form187_3"
            type="text"
            tabindex="66"
            value=""
            data-objref="788 0 R"
            data-field-name="Spells 1037"
          />
          <input
            id="form188_3"
            type="text"
            tabindex="131"
            value=""
            data-objref="633 0 R"
            data-field-name="Spells 1084"
          />
          <input
            id="form189_3"
            type="text"
            tabindex="130"
            value=""
            data-objref="632 0 R"
            data-field-name="Spells 1083"
          />
          <input
            id="form190_3"
            type="text"
            tabindex="65"
            value=""
            data-objref="787 0 R"
            data-field-name="Spells 1036"
          />
          <input
            id="form191_3"
            type="text"
            tabindex="129"
            value=""
            data-objref="631 0 R"
            data-field-name="Spells 1082"
          />
          <input
            id="form192_3"
            type="text"
            tabindex="64"
            value=""
            data-objref="786 0 R"
            data-field-name="Spells 1035"
          />
          <input
            id="form193_3"
            type="text"
            tabindex="8"
            value=""
            data-objref="816 0 R"
            data-field-name="Spells 1015"
          />
          <input
            id="form194_3"
            type="text"
            tabindex="3"
            value=""
            data-objref="808 0 R"
            data-field-name="SpellSaveDC  2"
          />
          <input
            id="form195_3"
            type="text"
            tabindex="4"
            value=""
            data-objref="809 0 R"
            data-field-name="SpellAtkBonus 2"
          />
          <input
            id="form196_3"
            type="text"
            tabindex="2"
            value=""
            data-objref="767 0 R"
            data-field-name="SpellcastingAbility 2"
          />
          <input
            id="form197_3"
            type="text"
            tabindex="92"
            value=""
            data-objref="821 0 R"
            data-field-name="SlotsRemaining 22"
          />
          <input
            id="form198_3"
            type="text"
            tabindex="15"
            value=""
            data-objref="743 0 R"
            data-field-name="Spells 1022"
          />
          <input
            id="form199_3"
            type="text"
            tabindex="14"
            value=""
            data-objref="742 0 R"
            data-field-name="Spells 1021"
          />
          <input
            id="form200_3"
            type="text"
            tabindex="13"
            value=""
            data-objref="741 0 R"
            data-field-name="Spells 1020"
          />
          <input
            id="form201_3"
            type="text"
            tabindex="12"
            value=""
            data-objref="820 0 R"
            data-field-name="Spells 1019"
          />
          <input
            id="form202_3"
            type="text"
            tabindex="11"
            value=""
            data-objref="819 0 R"
            data-field-name="Spells 1018"
          />
          <input
            id="form203_3"
            type="text"
            tabindex="10"
            value=""
            data-objref="818 0 R"
            data-field-name="Spells 1017"
          />
          <input
            id="form204_3"
            type="text"
            tabindex="9"
            value=""
            data-objref="817 0 R"
            data-field-name="Spells 1016"
          />
          <input
            id="form205_3"
            type="text"
            tabindex="62"
            value=""
            data-objref="784 0 R"
            data-field-name="SlotsRemaining 20"
          />
          <input
            id="form206_3"
            type="text"
            tabindex="117"
            value=""
            data-objref="662 0 R"
            data-field-name="SlotsRemaining 23"
          />
          <input
            id="form207_3"
            type="text"
            tabindex="6"
            value=""
            data-objref="811 0 R"
            data-field-name="SlotsRemaining 19"
          />
          <input
            id="form208_3"
            type="text"
            tabindex="178"
            value=""
            data-objref="669 0 R"
            data-field-name="SlotsRemaining 27"
          />
          <input
            id="form209_3"
            type="text"
            tabindex="77"
            value=""
            data-objref="794 0 R"
            data-field-name="SlotsRemaining 21"
          />
          <input
            id="form210_3"
            type="text"
            tabindex="150"
            value=""
            data-objref="587 0 R"
            data-field-name="SlotsRemaining 26"
          />
          <input
            id="form211_3"
            type="text"
            tabindex="139"
            value=""
            data-objref="621 0 R"
            data-field-name="SlotsRemaining 25"
          />
          <input
            id="form212_3"
            type="text"
            tabindex="128"
            value=""
            data-objref="630 0 R"
            data-field-name="SlotsRemaining 24"
          />
          <input
            id="form213_3"
            type="text"
            tabindex="7"
            value=""
            data-objref="812 0 R"
            data-field-name="Spells 1014"
          />
          <input
            id="form214_3"
            type="text"
            tabindex="1"
            value=""
            data-objref="766 0 R"
            data-field-name="Spellcasting Class 2"
          />
        </form>
        <!-- End Form Data -->
      </div>
    </div>

  

    <div id="scripts">
      <!--- Functions created by the PDF to HTML5 converter, to handle checkboxes -->
      <script src="build/checkboxes.js"></script>

      <!--- Main script that edits the HTML data -->
      <script id="logical" src="build/logical_version.js"></script>

      <!--- Script that chooses which version to do -->
      <script src="build/chooseVersion.js"></script>

      <!-- Script used for extra sylings for CSS -->
      <script src="build/styling.js"></script>

      <!-- Script used for dropdown functionality -->
      <script src="build/dropdowns.js"></script>

      <!-- Script used for testing purposes -->
      <!-- <script src="build/testing.js"></script> -->
    </div>

  </body>
</html>