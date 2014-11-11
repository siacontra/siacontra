<?php
switch ($_POST['month']){
	case 1:
		echo '1,3,4,5,10,17,19,23';
		break;
	case 2:
		echo '12,13,15,20,28';
		break;
	case 3:
		echo '17';
		break;
	case 4:
		echo '2,3,25';
		break;
	case 5:
		echo '14,28';
		break;
	case 6:
		echo '11,12,13,14,19,20,21,28';
		break;
	case 7:
		echo '6,7,8,21,30';
		break;
	case 8:
		echo '11,14,15,23,25,26,27,28';
		break;
	case 9:
		echo '18,19,21,22,25,27,28,29';
		break;
	case 10:
		echo '1,2,3,4,5,6,7,8,9,10,11,12,13,19,20,21,22,23,24';
		break;
	case 11:
		echo '1,3,5,7,9,11,13,15,17,19,21,23,25,27,29';
		break;
	case 12:
		echo '5,6,7,8,9,22,23,24,25,29,30,31';
		break;
}
?>