var stateByCountry = {
	India: ["ANDAMAN & NICOBAR ISLANDS", "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar","Chandigarh", "Chhattisgarh", "D&NH and D&D", "Delhi", "Goa",
	"Gujarat", "Haryana", "Himachal Pradesh", "Jammu & Kashmir", "Jharkhand", "Karnataka", "Kerla", "Ladakh","Lakshadweep", "Madhya Pradesh",
	"Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Puduchery", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana",
	"Tripura", "Uttar Pradesh", "Uttarakhand", "West Bengal"]
}

function makeStateMenu(value) {
	if(value.length==0){
		document.getElementById("stateSelect").innerHTML = "<option></option>";
	}
	else {
		var stateOptions = "<option value=''>Select State</option>";
		for(stateId in stateByCountry[value]) {
			stateOptions+="<option>"+stateByCountry[value][stateId]+"</option>";
		}
		document.getElementById("stateSelect").innerHTML = stateOptions;
	}
}

var citiesByState = {		
	"ANDAMAN & NICOBAR ISLANDS": ["Nicobars", "North And Middle Andaman", "South Andaman"],

	"Andhra Pradesh": ["Anantapur", "Chittoor", "East Godavari", "Guntur", "Kadapa", "Krishna", "Kurnool", "Nellore", "Prakasam", "Srikakulam", "Visakhapatnam",
	"Vizianagaram", "West Godavari"],

	"Arunachal Pradesh": ["Anjaw", "Changlang", "Dibang Valley", "East Kameng", "East Siang", "Kamle", "Kra Daadi", "Kurung Kumey", "Lemmi", "Lepa Rada", "Lohit",
	"Longding", "Lower Dibang Valley", "Lower Siang", "Lower Subansiri", "Namsai", "Papum Pare", "Shi Yomi", "Siang", "Tawang", "Tirap",
	"Upper Siang", "Upper Subansiri", "West Kameng", "West Siang"], 

	"Assam": ["Bajali", "Baksha", "Barpeta", "Biswanath", "Bongaigaon", "Cachar", "Charaideo", "Chirang", "Darrang", "Dhemaji", "Dhubri", "Dibrugarh", "Goalpara",
	"Golaghat", "Hailakandi", "Hojai", "Jorhat", "Kamrup", "Kamrup Metropolitan", "Karbi Anglong", "Karimganj", "Kokrajhar", "Lakhimpur", "Majuli",
	"Morigaon", "Nagaon", "Nalbari", "North Cachar Hills", "Sivasagar", "Sonitpur", "South Salmara Mancachar", "Tinsukia", "Udalguri", 
		"West Karbi Anglong"],
 
	"Bihar": ["Araria", "Arwal", "Aurangabad", "Banka", "Begusarai", "Bhagalpur", "Bhojpur", "Buxar", "Darbhanga", "Gaya", "Gopalganj", "Jamui", "Jehanabad", "Kaimur(bhabua)",
	"Katihar", "Khagaria", "Kishanganj", "Lakhisarai", "Madhepura", "Madhubani", "Munger", "Muzaffarpur", "Nalanda", "Nawada", "Pashchim Champaran",
	"Patna", "Purba Champaran", "Purnia", "Rohtas", "Saharsa", "Samastipur", "Saran", "Sheikhpura", "Sheohar", "Sitamarhi", "Siwan", "Supaul", "Vaishali"],
	
	"Chandigarh": ["Chandigarh"],

	"Chhattisgarh": ["Balod", "Baloda Bazar", "Balrampur", "Bastar", "Bemetara", "Bijapur", "Bilaspir", "Dantewada", "Dhamtari", "Durg", "Gariyaband", 
		"Gaurela Pendra Marwahi", "Jannjgir-champa", "Jashpur", "Kanker", "Kawardha", "Kondagaon", "Korba", "Koriya", "Mahasamund", "Manendragarh-Chirmiri-Bharatpur",
		"Mohla Manpur", "Mungeli", "Narayanpur", "Raigarh", "Raipur", "Rajnandagon", "Sarangarh-Bilaigarh", "Shakti", "Sukma", "Surajpur", "Surguja"],
	
	"D&NH and D&D": ["Dadra And Nagar Haveli", "Daman", "Diu"],

	"Delhi": ["Central Delhi", "East Delhi", "New Delhi", "North Delhi", "North East Delhi", "North West Delhi", "Shahdara", "South Delhi", "South East Delhi",
	"South West Delhi", "West Delhi"],


	"Goa": ["North Goa", "South Goa"],
	
	"Gujarat": ["Ahmedabad", "Amreli", "Anand", "Aravalli", "Banas Kantha", "Bharuch", "Bhavnagar", "Botad", "Chhotaudepur", "Dang", "Devbhoomi Dwarka", "Dohad", 
		"Gandhinagar", "Gir Somnath", "Jamnagar", "Junagadh", "Kachchh", "Kheda", "Mahesana", "Mahisagar", "Morbi", "Narmada", "Navsari", "Panch Mahals", 
		"Patan", "Porbandar", "Rajkot", "Sabar Kantha", "Surat", "Surendranagar", "Tapi", "Vadodara", "Valsad"],

	"Haryana": ["Ambala", "Bhiwani", "Charkhi Dadri", "Faridabad", "Fatehabad", "Gurgaon", "Hisar", "Jhajjar", "Jind", "Kaithal", "Karnal", "Kurukshetra", 
		"Mahendragarh", "Mewat", "Palwal", "Panchkula", "Panipat", "Rewari", "Rohtak", "Sirsa", "Sonipat", "Yamunanagar"],

	"Himachal Pradesh": ["Bilaspur", "Chamba", "Hamirpur", "Kangra", "Kinnaur", "Kullu", "Lahul And Spiti", "Mandi", "Shimla", "Sirmaur", "Solan", "Una"],

	"Jammu & Kashmir": ["Anantnag", "Bandipora", "Baramulla", "Budgam", "Doda", "Ganderbal", "Jammu", "Kathua", "Kishtwar", "Kulgam", "Kupwara", "Poonch", "Pulwama",
	"Rajauri", "Ramban", "Reasi", "Samba", "Shopian", "Srinagar", "Udhampur"],


	"Jharkhand": ["Bokaro", "Chatra", "Deoghar", "Dhanbad", "Dumka", "Garhwa", "Giridih", "Godda", "Hazaribag", "Jamtara", "Khuti", "Koderma", "Latehar", 
	"Lohardaga", "Pakur", "Palamu", "Paschim Singhbhum", "Purbi Singhbhum", "Ramgarh", "Ranchi", "Sahibganj", "Sareikela And Kharsawan", "Simdega"],
	

	"Karnataka": ["Bagalkote", "Ballari", "Bangalore Rural", "Belagavi", "Bengaluru Urban", "Bidar", "Chamarajanagar", "Chik Ballapur", "Chikmagalur", "Chitradurga",
	"Dakshina Kannada", "Davangere", "Dharwad", 'Gadag", "Hassan", "Haveri", "Kalaburagi', "Kodagu", 'Kolar', "Koppal", "Mandya", "Mysuru", "Raichur", 
	"Ramanagaram", "Shivamogga", "Tumkur", "Udupi", "Uttara Kannada", "Vijayanagara", "Vijayapura(bijapur)", "Yadgir"],


	"Kerla": ["Alappuzha", "Ernakulam", "Idukki", "Kannur", "Kasaragod", "Kollam", "Kottayam", "Kozhikode", "Malappuram", "Palakkad", "Pathanamthitta",
	"Thiruvananthapuram", "Thrissur", "Wayanad"],


	"Ladakh": ["Kargil", "Leh(Ladakh)"],

	"Lakshadweep": ["Lakshadweep"],

	"Madhya Pradesh": ["Agar", "Alirajpur", "Anuppur", "Ashoknagar", "Balaghat", "Barwani", "Betul", "Bhind", "Bhopal", "Burhanpur", "Chachaura Binaganj",
	"Chhatarpur", "Chhindwara",	"Damoh", "Datia", "Dewas", "Dhar", "Dindori", "Guna", "Gwalior", "Harda", "Hoshangabad", "Indore", "Jabalpur",
	"Jhabua", "Katni", "Khandwa", "Khargone", "Maihar", "Mandla", "Mandsaur", "Morena", "Narsinghpur", "Neemuch", "Niwari", "Panna", "Raisen", "Rajgarh",
	"Ratlam","Rewa", "Sagar", "Satna", "Sehore", "Seoni", "Shahdol", "Shajapur", "Sheopur", "Shivpuri", "Sidhi", "Singrouli", "Tikamgarh", "Ujjain","Umaria", "Vidisha"],


	"Maharashtra": ["Ahmednagar", "Akola", "Amravati", "Aurangabad", "Beed", "Bhandara", "Buldana", "Chandrapur", "Dhule", "Gadchiroli", "Gondia", "Hingoli", 
	"Jalgaon", "Jalna", "Kolhapur", "Latur", "Mumbai City", "Mumbai suburban", "Nagpur", "Nanded", "Nandurbar", "Nashik", "Osmanabad", "Palghar",
	"Parbhani", "Pune", "Raigad", "Ratnagiri", "Sangli", "Satara", "Sindhudurg", "Solapur", "Thane", "Wardha", "Washim ", "Yavatmal"],


	"Manipur": ["Bishnupur", "Chandel", "Churachandpur", "Imphal East", "Imphal West", "Jiribam", "Kakching", "Kamjong", "Kangpokpi", "Noney", "Pherzawl",
	"Senapati", "Tamenglong", "Tengnoupal", "Thoubal", "Ukhrul"],


 	"Meghalaya": ["East Garo Hills", "East Jaintia Hills", "East Khasi Hills", "North Garo Hills", "Ri Bhoi", "South Garo Hills", "South West Garo Hills", 
		"South West Khasi Hills", "West Garo Hills", "West Jaintia Hills", "West Khasi Hills"],

	"Mizoram": ["Aizawl", "Champhai", "Hnahthial", "Khawzawl", "Kolasib", "Lawngtlai", "Lunglei", "Mamit", "Saiha", "Saitual", "Serchhip"],

	"Nagaland": ["Dimapur", "Kiphire", "Kohima", "Longleng", "Mokokchung", "Mon", "Noklak", "Peren", "Phek", "Tuensang", "Wokha", "Zunheboto"],

	"Odisha": ["Angul", "Boudh (Bauda)", "Bhadrak", "Balangir", "Bargarh (Baragarh)", "Balasore", "Cuttack", "Debagarh (Deogarh)", "Dhenkanal", "Ganjam", "Gajapati",
		"Jharsuguda", "Jajpur", "Jagatsinghpur", "Khordha", "Kendujhar", "Kalahandi", "Kandhamal", "Koraput", "Kendrapara", "Malkangiri", "Mayurbhanj",
		"Nabarangpur", "Nuapada", "Nayagarh", "Puri", "Rayagada", "Sambalpur", "Subarnapur (Sonepur)", "Sundargarh"],

	"Puduchery": ["Karaikal", "Mahé", "Puducherry", "Yanam"],

	"Punjab": ["Amritsar", "Barnala", "Bathinda", "Firozpur", "Faridkot", "Fatehgarh Sahib", "Fazilka", "Gurdaspur", "Hoshiarpur", "Jalandhar", "Kapurthala", 
	"Ludhiana","Malerkotla", "Mansa", "Moga", "Sri Muktsar Sahib", "Pathankot", "Patiala", "Rupnagar", "Sahibzada Ajit Singh Nagar", "Sangrur", 
		"Shahid Bhagat Singh Nagar", "Tarn Taran"],

	"Rajasthan": ["Ajmer", "Alwar", "Bikaner", "Barmer", "Banswara", "Bharatpur", "Baran", "Bundi", "Bhilwara", "Churu", "Chittorgarh", "Dausa", "Dholpur",
	"Dungarpur", "Sri Ganganagar", "Hanumangarh", "Jhunjhunu", "Jalore", "Jodhpur", "Jaipur", "Jaisalmer", "Jhalawar", "Karauli", "Kota", "Nagaur", "Pali",
	"Pratapgarh", "Rajsamand", "Sikar", "Sawai Madhopur", "Sirohi", "Tonk", "Udaipur"],
	
	"Sikkim": ["Gangtok", "North Sikkim", "Pakyong", "Soreng", "South Sikkim", "West Sikkim"],

  	"Tamil Nadu": ["Ariyalur", "Chengalpattu", "Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Kallakurichi", "Kanchipuram", "Kanyakumari",
	"Karur", "Krishnagiri", "Madurai", "Mayiladuthurai", "Nagapattinam", "Nilgiris", "Namakkal", "Perambalur", "Pudukkottai", "Ramanathapuram",
	"Ranipet", "Salem", "Sivaganga", "Tenkasi", "Tiruppur", "Tiruchirappalli", "Theni", "Tirunelveli", "Thanjavur", "Thoothukudi", "Tirupattur",
	"Tiruvallur", "Tiruvarur", "Tiruvannamalai", "Vellore", "Viluppuram", "Virudhunagar"],

	"Telangana": ["Adilabad", "Bhadradri Kothagudem", "Hanamkonda", "Hyderabad", "Jagtial", "Jangaon", "Jayashankar Bhupalpally", "Jogulamba Gadwal", "Kamareddy",
	"Karimnagar", "Khammam", "Kumuram Bheem Asifabad", "Mahabubabad", "Mahbubnagar", "Mancherial", "Medak", "Medchal–Malkajgiri", "Mulugu",
	"Nalgonda", "Narayanpet", "Nagarkurnool", "Nirmal", "Nizamabad", "Peddapalli", "Rajanna Sircilla", "Ranga Reddy", "Sangareddy", "Siddipet",
	"Suryapet", "Vikarabad", "Wanaparthy", "Warangal", "Yadadri Bhuvanagiri"],

	"Tripura": ["Dhalai", "Gomati", "Khowai", "North Tripura", "Sepahijala", "South Tripura", "Unokoti", "West Tripura"],

	"Uttar Pradesh": ["Agra", "Aligarh", "Ayodhya", "Ambedkar Nagar", "Amethi", "Amroha", "Auraiya", "Azamgarh", "Bagpat", "Bahraich", "Ballia", "Balrampur",
	"Banda", "Barabanki", "Bareilly", "Basti", "Bhadohi", "Bijnor", "Budaun", "Bulandshahr", "Chandauli", "Chitrakoot", "Deoria", "Etah", "Etawah",
	"Farrukhabad", "Fatehpur", "Firozabad", "Gautam Buddha Nagar", "Ghaziabad", "Ghazipur", "Gonda", "Gorakhpur", "Hamirpur", "Hapur", "Hardoi",
	"Hathras", "Jalaun", "Jaunpur", "Jhansi", "Kannauj", "Kanpur Dehat", "Kanpur Nagar", "Kasganj", "Kaushambi", "Kushinagar", "Lakhimpur Kheri",
	"Lalitpur", "Lucknow", "Maharajganj", "Mahoba", "Mainpuri", "Mathura", "Mau", "Meerut", "Mirzapur", "Moradabad", "Muzaffarnagar", "Pilibhit",
	"Pratapgarh", "Prayagraj", "Raebareli", "Rampur", "Saharanpur", "Sambhal", "Sant Kabir Nagar", "Shahjahanpur", "Shamli", "Shravasti",
	"Siddharthnagar", "Sitapur", "Sonbhadra", "Sultanpur", "Unnao", "Varanasi"],

 	"Uttarakhand": ["Almora", "Bageshwar", "Chamoli", "Champawat", "Dehradun", "Didihat", "Haridwar", "Kotdwar", "Nainital", "Pauri Garhwal", "Pithoragarh",
	"Ranikhet", "Rudraprayag", "Tehri Garhwal", "Udham Singh Nagar", "Uttarkashi", "Yamunotri"],

	"West Bengal": ["Alipurduar", "Bankura", "Paschim Bardhaman", "Purba Bardhaman", "Birbhum", "Cooch Behar", "Dakshin Dinajpur", "Darjeeling", "Hooghly", "Howrah",
	"Jalpaiguri", "Jhargram", "Kalimpong", "Kolkata", "Maldah", "Murshidabad", "Nadia", "North 24 Parganas", "Paschim Medinipur", "Purba Medinipur",
	"Purulia", "South 24 Parganas", "Uttar Dinajpur"]
	
}

function makeSubmenu(value) {
	if(value.length==0){
		document.getElementById("citySelect").innerHTML = "<option value=''>Select City</option>";
		document.getElementById("citySelect").setAttribute("readonly","");
	}
	else {
		var citiesOptions = "<option value=''>Select City</option>";
		for(cityId in citiesByState[value]) {
			citiesOptions+="<option>"+citiesByState[value][cityId]+"</option>";
		}
		document.getElementById("citySelect").innerHTML = citiesOptions;
		document.getElementById("citySelect").removeAttribute("readonly");
	}
}

/*function resetSelection() {
	document.getElementById("countrySelect").selectedIndex = 0;
	document.getElementById("citySelect").selectedIndex = 0;
}*/