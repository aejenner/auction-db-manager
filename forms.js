
//constants
var CLICK_TO_EXPAND = "Click to Expand";
var CLICK_TO_COLLPASE = "Click to Collapse";
var DISPLAY_NONE = "display: none;";
var DISPLAY_BLOCK = "display: block;";

//forms & buttons
var userForm, lotForm, reportsForm;
var userButton, lotButton, reportsButton;

//whether or not a form is being displayed
var showingUser, showingLot, showingReports;

//form fields
var firstName, lastName, userBuyerID, userSellerID, email;
var lotNumber, lotBuyerID, lotSellerID, lotPrice, lotTitle;

//user "load" functions
var userLoads = new Array();

//lot "load" functions
var lotLoads = new Array();

var onLoadFunction = function() {
  showingUser = false;
  showingLot = false;
  showingReports = false;

  userForm = document.getElementById("userInfo");
  lotForm = document.getElementById("lotInfo");
  reportsForm = document.getElementById("reportsInfo");

  //userForm.style = DISPLAY_NONE;
  //lotForm.style = DISPLAY_NONE;
  //reportsForm.style = DISPLAY_NONE;

  document.getElementById("smallFont").onclick = smallerButton;
  document.getElementById("medFont").onclick = medButton;
  document.getElementById("bigFont").onclick = bigButton;

  userButton = document.getElementById("userButton");
  lotButton = document.getElementById("lotButton");
  reportsButton = document.getElementById("reportsButton");
  
  userButton.onclick = userInfo;
  lotButton.onclick = lotInfo;
  reportsButton.onclick = reportsInfo;
  
  //var fistName, lastName, userBuyerID, userSellerID, email;
  firstName = document.getElementById("userFirstName");
  lastName = document.getElementById("userLastName");
  userBuyerID = document.getElementById("userBuyerID");
  userSellerID = document.getElementById("userSellerID");
  email = document.getElementById("userEmail");
  
  //var lotNumber, lotBuyerID, lotSellerID, lotPrice, lotTitle;
  lotNumber = document.getElementById("lotNumber");
  lotBuyerID = document.getElementById("lotBuyerID");
  lotSellerID = document.getElementById("lotSellerID");
  lotPrice = document.getElementById("lotPrice");
  lotTitle = document.getElementById("lotTitle");
  
  //register the onkeyup events for the fields
  //document.getElementById("userFirstName").onkeyup = ???;
  /*
   * names are a problem with this but they didn't ask for it so they don't need
   * it.
   */
  
  lotNumber.onkeyup = lotNumberLoad;
};

var smallerButton = function() {
  document.body.style = "font-size: medium;";
};

var medButton = function() {
  document.body.style = "font-size: x-large;";
};

var bigButton = function() {
  document.body.style = "font-size: xx-large;";
};

var userInfo = function() {
  showingUser = !showingUser;

  if (showingUser) {
    userForm.style = DISPLAY_BLOCK;
  } else {
    userForm.style = DISPLAY_NONE;
  }
};

var lotInfo = function() {
  showingLot = !showingLot;

  if (showingLot) {
    lotForm.style = DISPLAY_BLOCK;
  } else {
    lotForm.style = DISPLAY_NONE;
  }
};

var reportsInfo = function() {
  showingReports = !showingReports;

  if (showingReports) {
    reportsForm.style = DISPLAY_BLOCK;
  } else {
    reportsForm.style = DISPLAY_NONE;
  }
};

var lotNumberLoad = function() {
  if (lotNumber.value === "") return;
  if (lotLoads[lotNumber.value]) lotLoads[lotNumber.value]();
};
