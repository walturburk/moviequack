<?php

function getEmojiBoard() {
	$emoji = getEmojiId();
	//ksort($emoji);
	$html = "<ul class='emojiboard'>";
	foreach ($emoji AS $key => $value) {
		$html .= "<li data-emoji='".$key."'>".getEmoji($value)."</li>";
	}
	$html .= "</ul>";
	return $html;
}

function getEmoji($shortname = "emoticon", $size = "medium", $attr = "") {
	if ($shortname == "emoticon") {
		$attr .= "";
	} else {
		$attr .= "";
	}
	if (strpos($shortname, ":") > -1) {
		$emoji = getEmojiId($shortname);
	} else {
		$emoji = $shortname;
	}
	$htmlimg = "";
	//" onload='jQuery(this).fadeIn();' ";
		if ($size == "medium") {
			$htmlimg = '<img '.$attr.' src="/emoji/png/'.$emoji.'.png"/>';
		} else if ($size == "large") {
			$htmlimg = '<img '.$attr.' src="/emoji/png_128x128/'.$emoji.'.png"/>';
		} else if ($size == "xl") {
			$htmlimg = '<img '.$attr.' src="/emoji/png_512x512/'.$emoji.'.png"/>';
		} else if ($size == "bw") {
			$htmlimg = '<img '.$attr.' src="/emoji/png_bw/'.$emoji.'.png"/>';
		} else if ($size == "svg") {
			$htmlimg = '<img '.$attr.' src="/emoji/svg/'.$emoji.'.svg"/>';
		} else if ($size == "svgbw") {
			$htmlimg = '<img '.$attr.' src="/emoji/svg_bw/'.$emoji.'.svg"/>';
		}

	return $htmlimg;
}

function getEmojiId($shortname = null) {

	$emoji = array(
        ':grinning:' => '1f600',
		':grimacing:' => '1f62c',
		':grin:' => '1f601',
		':joy:' => '1f602',
		':smiley:' => '1f603',
		':smile:' => '1f604',
		':sweat_smile:' => '1f605',
		':laughing:' => '1f606',
		':innocent:' => '1f607',
		':wink:' => '1f609',
		':blush:' => '1f60a',
		':slight_smile:' => '1f642',
		':upside_down:' => '1f643',
		':relaxed:' => '263a',
		':yum:' => '1f60b',
		':relieved:' => '1f60c',
		':heart_eyes:' => '1f60d',
		':kissing_heart:' => '1f618',
		':kissing:' => '1f617',
		':kissing_smiling_eyes:' => '1f619',
		':kissing_closed_eyes:' => '1f61a',
		':stuck_out_tongue_winking_eye:' => '1f61c',
		':stuck_out_tongue_closed_eyes:' => '1f61d',
		':stuck_out_tongue:' => '1f61b',
		':money_mouth:' => '1f911',
		':nerd:' => '1f913',
		':sunglasses:' => '1f60e',
		':hugging:' => '1f917',
		':smirk:' => '1f60f',
		':no_mouth:' => '1f636',
		':neutral_face:' => '1f610',
		':expressionless:' => '1f611',
		':unamused:' => '1f612',
		':rolling_eyes:' => '1f644',
		':thinking:' => '1f914',
		':flushed:' => '1f633',
		':disappointed:' => '1f61e',
		':worried:' => '1f61f',
		':angry:' => '1f620',
		':rage:' => '1f621',
		':pensive:' => '1f614',
		':confused:' => '1f615',
		':slight_frown:' => '1f641',
		':frowning2:' => '2639',
		':persevere:' => '1f623',
		':confounded:' => '1f616',
		':tired_face:' => '1f62b',
		':weary:' => '1f629',
		':triumph:' => '1f624',
		':open_mouth:' => '1f62e',
		':scream:' => '1f631',
		':fearful:' => '1f628',
		':cold_sweat:' => '1f630',
		':hushed:' => '1f62f',
		':frowning:' => '1f626',
		':anguished:' => '1f627',
		':cry:' => '1f622',
		':disappointed_relieved:' => '1f625',
		':sleepy:' => '1f62a',
		':sweat:' => '1f613',
		':sob:' => '1f62d',
		':dizzy_face:' => '1f635',
		':astonished:' => '1f632',
		':zipper_mouth:' => '1f910',
		':mask:' => '1f637',
		':thermometer_face:' => '1f912',
		':head_bandage:' => '1f915',
		':sleeping:' => '1f634',
		':zzz:' => '1f4a4',
		':poop:' => '1f4a9',
		':smiling_imp:' => '1f608',
		':imp:' => '1f47f',
		':japanese_ogre:' => '1f479',
		':japanese_goblin:' => '1f47a',
		':skull:' => '1f480',
		':ghost:' => '1f47b',
		':alien:' => '1f47d',
		':robot:' => '1f916',
		':smiley_cat:' => '1f63a',
		':smile_cat:' => '1f638',
		':joy_cat:' => '1f639',
		':heart_eyes_cat:' => '1f63b',
		':smirk_cat:' => '1f63c',
		':kissing_cat:' => '1f63d',
		':scream_cat:' => '1f640',
		':crying_cat_face:' => '1f63f',
		':pouting_cat:' => '1f63e',
		':raised_hands:' => '1f64c',
		':clap:' => '1f44f',
		':wave:' => '1f44b',
		':thumbsup:' => '1f44d',
		':thumbsdown:' => '1f44e',
		':punch:' => '1f44a',
		':fist:' => '270a',
		':v:' => '270c',
		':ok_hand:' => '1f44c',
		':raised_hand:' => '270b',
		':open_hands:' => '1f450',
		':muscle:' => '1f4aa',
		':pray:' => '1f64f',
		':point_up:' => '261d',
		':point_up_2:' => '1f446',
		':point_down:' => '1f447',
		':point_left:' => '1f448',
		':point_right:' => '1f449',
		':middle_finger:' => '1f595',
		':hand_splayed:' => '1f590',
		':metal:' => '1f918',
		':vulcan:' => '1f596',
		':writing_hand:' => '270d',
		':nail_care:' => '1f485',
		':lips:' => '1f444',
		':tongue:' => '1f445',
		':ear:' => '1f442',
		':nose:' => '1f443',
		':eye:' => '1f441',
		':eyes:' => '1f440',
		':bust_in_silhouette:' => '1f464',
		':busts_in_silhouette:' => '1f465',
		':speaking_head:' => '1f5e3',
		':baby:' => '1f476',
		':boy:' => '1f466',
		':girl:' => '1f467',
		':man:' => '1f468',
		':woman:' => '1f469',
		':person_with_blond_hair:' => '1f471',
		':older_man:' => '1f474',
		':older_woman:' => '1f475',
		':man_with_gua_pi_mao:' => '1f472',
		':man_with_turban:' => '1f473',
		':cop:' => '1f46e',
		':construction_worker:' => '1f477',
		':guardsman:' => '1f482',
		':spy:' => '1f575',
		':santa:' => '1f385',
		':angel:' => '1f47c',
		':princess:' => '1f478',
		':bride_with_veil:' => '1f470',
		':walking:' => '1f6b6',
		':runner:' => '1f3c3',
		':dancer:' => '1f483',
		':dancers:' => '1f46f',
		':couple:' => '1f46b',
		':two_men_holding_hands:' => '1f46c',
		':two_women_holding_hands:' => '1f46d',
		':bow:' => '1f647',
		':information_desk_person:' => '1f481',
		':no_good:' => '1f645',
		':ok_woman:' => '1f646',
		':raising_hand:' => '1f64b',
		':person_with_pouting_face:' => '1f64e',
		':person_frowning:' => '1f64d',
		':haircut:' => '1f487',
		':massage:' => '1f486',
		':couple_with_heart:' => '1f491',
		':couple_ww:' => '1f469-2764-1f469',
		':couple_mm:' => '1f468-2764-1f468',
		':couplekiss:' => '1f48f',
		':kiss_ww:' => '1f469-2764-1f48b-1f469',
		':kiss_mm:' => '1f468-2764-1f48b-1f468',
		':family:' => '1f46a',
		':family_mwg:' => '1f468-1f469-1f467',
		':family_mwgb:' => '1f468-1f469-1f467-1f466',
		':family_mwbb:' => '1f468-1f469-1f466-1f466',
		':family_mwgg:' => '1f468-1f469-1f467-1f467',
		':family_wwb:' => '1f469-1f469-1f466',
		':family_wwg:' => '1f469-1f469-1f467',
		':family_wwgb:' => '1f469-1f469-1f467-1f466',
		':family_wwbb:' => '1f469-1f469-1f466-1f466',
		':family_wwgg:' => '1f469-1f469-1f467-1f467',
		':family_mmb:' => '1f468-1f468-1f466',
		':family_mmg:' => '1f468-1f468-1f467',
		':family_mmgb:' => '1f468-1f468-1f467-1f466',
		':family_mmbb:' => '1f468-1f468-1f466-1f466',
		':family_mmgg:' => '1f468-1f468-1f467-1f467',
		':womans_clothes:' => '1f45a',
		':shirt:' => '1f455',
		':jeans:' => '1f456',
		':necktie:' => '1f454',
		':dress:' => '1f457',
		':bikini:' => '1f459',
		':kimono:' => '1f458',
		':lipstick:' => '1f484',
		':kiss:' => '1f48b',
		':footprints:' => '1f463',
		':high_heel:' => '1f460',
		':sandal:' => '1f461',
		':boot:' => '1f462',
		':mans_shoe:' => '1f45e',
		':athletic_shoe:' => '1f45f',
		':womans_hat:' => '1f452',
		':tophat:' => '1f3a9',
		':helmet_with_cross:' => '26d1',
		':mortar_board:' => '1f393',
		':crown:' => '1f451',
		':school_satchel:' => '1f392',
		':pouch:' => '1f45d',
		':purse:' => '1f45b',
		':handbag:' => '1f45c',
		':briefcase:' => '1f4bc',
		':eyeglasses:' => '1f453',
		':dark_sunglasses:' => '1f576',
		':ring:' => '1f48d',
		':closed_umbrella:' => '1f302',
		':raised_hands_tone1:' => '1f64c-1f3fb',
		':raised_hands_tone2:' => '1f64c-1f3fc',
		':raised_hands_tone3:' => '1f64c-1f3fd',
		':raised_hands_tone4:' => '1f64c-1f3fe',
		':raised_hands_tone5:' => '1f64c-1f3ff',
		':clap_tone1:' => '1f44f-1f3fb',
		':clap_tone2:' => '1f44f-1f3fc',
		':clap_tone3:' => '1f44f-1f3fd',
		':clap_tone4:' => '1f44f-1f3fe',
		':clap_tone5:' => '1f44f-1f3ff',
		':wave_tone1:' => '1f44b-1f3fb',
		':wave_tone2:' => '1f44b-1f3fc',
		':wave_tone3:' => '1f44b-1f3fd',
		':wave_tone4:' => '1f44b-1f3fe',
		':wave_tone5:' => '1f44b-1f3ff',
		':thumbsup_tone1:' => '1f44d-1f3fb',
		':thumbsup_tone2:' => '1f44d-1f3fc',
		':thumbsup_tone3:' => '1f44d-1f3fd',
		':thumbsup_tone4:' => '1f44d-1f3fe',
		':thumbsup_tone5:' => '1f44d-1f3ff',
		':thumbsdown_tone1:' => '1f44e-1f3fb',
		':thumbsdown_tone2:' => '1f44e-1f3fc',
		':thumbsdown_tone3:' => '1f44e-1f3fd',
		':thumbsdown_tone4:' => '1f44e-1f3fe',
		':thumbsdown_tone5:' => '1f44e-1f3ff',
		':punch_tone1:' => '1f44a-1f3fb',
		':punch_tone2:' => '1f44a-1f3fc',
		':punch_tone3:' => '1f44a-1f3fd',
		':punch_tone4:' => '1f44a-1f3fe',
		':punch_tone5:' => '1f44a-1f3ff',
		':fist_tone1:' => '270a-1f3fb',
		':fist_tone2:' => '270a-1f3fc',
		':fist_tone3:' => '270a-1f3fd',
		':fist_tone4:' => '270a-1f3fe',
		':fist_tone5:' => '270a-1f3ff',
		':v_tone1:' => '270c-1f3fb',
		':v_tone2:' => '270c-1f3fc',
		':v_tone3:' => '270c-1f3fd',
		':v_tone4:' => '270c-1f3fe',
		':v_tone5:' => '270c-1f3ff',
		':ok_hand_tone1:' => '1f44c-1f3fb',
		':ok_hand_tone2:' => '1f44c-1f3fc',
		':ok_hand_tone3:' => '1f44c-1f3fd',
		':ok_hand_tone4:' => '1f44c-1f3fe',
		':ok_hand_tone5:' => '1f44c-1f3ff',
		':raised_hand_tone1:' => '270b-1f3fb',
		':raised_hand_tone2:' => '270b-1f3fc',
		':raised_hand_tone3:' => '270b-1f3fd',
		':raised_hand_tone4:' => '270b-1f3fe',
		':raised_hand_tone5:' => '270b-1f3ff',
		':open_hands_tone1:' => '1f450-1f3fb',
		':open_hands_tone2:' => '1f450-1f3fc',
		':open_hands_tone3:' => '1f450-1f3fd',
		':open_hands_tone4:' => '1f450-1f3fe',
		':open_hands_tone5:' => '1f450-1f3ff',
		':muscle_tone1:' => '1f4aa-1f3fb',
		':muscle_tone2:' => '1f4aa-1f3fc',
		':muscle_tone3:' => '1f4aa-1f3fd',
		':muscle_tone4:' => '1f4aa-1f3fe',
		':muscle_tone5:' => '1f4aa-1f3ff',
		':pray_tone1:' => '1f64f-1f3fb',
		':pray_tone2:' => '1f64f-1f3fc',
		':pray_tone3:' => '1f64f-1f3fd',
		':pray_tone4:' => '1f64f-1f3fe',
		':pray_tone5:' => '1f64f-1f3ff',
		':point_up_tone1:' => '261d-1f3fb',
		':point_up_tone2:' => '261d-1f3fc',
		':point_up_tone3:' => '261d-1f3fd',
		':point_up_tone4:' => '261d-1f3fe',
		':point_up_tone5:' => '261d-1f3ff',
		':point_up_2_tone1:' => '1f446-1f3fb',
		':point_up_2_tone2:' => '1f446-1f3fc',
		':point_up_2_tone3:' => '1f446-1f3fd',
		':point_up_2_tone4:' => '1f446-1f3fe',
		':point_up_2_tone5:' => '1f446-1f3ff',
		':point_down_tone1:' => '1f447-1f3fb',
		':point_down_tone2:' => '1f447-1f3fc',
		':point_down_tone3:' => '1f447-1f3fd',
		':point_down_tone4:' => '1f447-1f3fe',
		':point_down_tone5:' => '1f447-1f3ff',
		':point_left_tone1:' => '1f448-1f3fb',
		':point_left_tone2:' => '1f448-1f3fc',
		':point_left_tone3:' => '1f448-1f3fd',
		':point_left_tone4:' => '1f448-1f3fe',
		':point_left_tone5:' => '1f448-1f3ff',
		':point_right_tone1:' => '1f449-1f3fb',
		':point_right_tone2:' => '1f449-1f3fc',
		':point_right_tone3:' => '1f449-1f3fd',
		':point_right_tone4:' => '1f449-1f3fe',
		':point_right_tone5:' => '1f449-1f3ff',
		':middle_finger_tone1:' => '1f595-1f3fb',
		':middle_finger_tone2:' => '1f595-1f3fc',
		':middle_finger_tone3:' => '1f595-1f3fd',
		':middle_finger_tone4:' => '1f595-1f3fe',
		':middle_finger_tone5:' => '1f595-1f3ff',
		':hand_splayed_tone1:' => '1f590-1f3fb',
		':hand_splayed_tone2:' => '1f590-1f3fc',
		':hand_splayed_tone3:' => '1f590-1f3fd',
		':hand_splayed_tone4:' => '1f590-1f3fe',
		':hand_splayed_tone5:' => '1f590-1f3ff',
		':metal_tone1:' => '1f918-1f3fb',
		':metal_tone2:' => '1f918-1f3fc',
		':metal_tone3:' => '1f918-1f3fd',
		':metal_tone4:' => '1f918-1f3fe',
		':metal_tone5:' => '1f918-1f3ff',
		':vulcan_tone1:' => '1f596-1f3fb',
		':vulcan_tone2:' => '1f596-1f3fc',
		':vulcan_tone3:' => '1f596-1f3fd',
		':vulcan_tone4:' => '1f596-1f3fe',
		':vulcan_tone5:' => '1f596-1f3ff',
		':writing_hand_tone1:' => '270d-1f3fb',
		':writing_hand_tone2:' => '270d-1f3fc',
		':writing_hand_tone3:' => '270d-1f3fd',
		':writing_hand_tone4:' => '270d-1f3fe',
		':writing_hand_tone5:' => '270d-1f3ff',
		':nail_care_tone1:' => '1f485-1f3fb',
		':nail_care_tone2:' => '1f485-1f3fc',
		':nail_care_tone3:' => '1f485-1f3fd',
		':nail_care_tone4:' => '1f485-1f3fe',
		':nail_care_tone5:' => '1f485-1f3ff',
		':ear_tone1:' => '1f442-1f3fb',
		':ear_tone2:' => '1f442-1f3fc',
		':ear_tone3:' => '1f442-1f3fd',
		':ear_tone4:' => '1f442-1f3fe',
		':ear_tone5:' => '1f442-1f3ff',
		':nose_tone1:' => '1f443-1f3fb',
		':nose_tone2:' => '1f443-1f3fc',
		':nose_tone3:' => '1f443-1f3fd',
		':nose_tone4:' => '1f443-1f3fe',
		':nose_tone5:' => '1f443-1f3ff',
		':baby_tone1:' => '1f476-1f3fb',
		':baby_tone2:' => '1f476-1f3fc',
		':baby_tone3:' => '1f476-1f3fd',
		':baby_tone4:' => '1f476-1f3fe',
		':baby_tone5:' => '1f476-1f3ff',
		':boy_tone1:' => '1f466-1f3fb',
		':boy_tone2:' => '1f466-1f3fc',
		':boy_tone3:' => '1f466-1f3fd',
		':boy_tone4:' => '1f466-1f3fe',
		':boy_tone5:' => '1f466-1f3ff',
		':girl_tone1:' => '1f467-1f3fb',
		':girl_tone2:' => '1f467-1f3fc',
		':girl_tone3:' => '1f467-1f3fd',
		':girl_tone4:' => '1f467-1f3fe',
		':girl_tone5:' => '1f467-1f3ff',
		':man_tone1:' => '1f468-1f3fb',
		':man_tone2:' => '1f468-1f3fc',
		':man_tone3:' => '1f468-1f3fd',
		':man_tone4:' => '1f468-1f3fe',
		':man_tone5:' => '1f468-1f3ff',
		':woman_tone1:' => '1f469-1f3fb',
		':woman_tone2:' => '1f469-1f3fc',
		':woman_tone3:' => '1f469-1f3fd',
		':woman_tone4:' => '1f469-1f3fe',
		':woman_tone5:' => '1f469-1f3ff',
		':person_with_blond_hair_tone1:' => '1f471-1f3fb',
		':person_with_blond_hair_tone2:' => '1f471-1f3fc',
		':person_with_blond_hair_tone3:' => '1f471-1f3fd',
		':person_with_blond_hair_tone4:' => '1f471-1f3fe',
		':person_with_blond_hair_tone5:' => '1f471-1f3ff',
		':older_man_tone1:' => '1f474-1f3fb',
		':older_man_tone2:' => '1f474-1f3fc',
		':older_man_tone3:' => '1f474-1f3fd',
		':older_man_tone4:' => '1f474-1f3fe',
		':older_man_tone5:' => '1f474-1f3ff',
		':older_woman_tone1:' => '1f475-1f3fb',
		':older_woman_tone2:' => '1f475-1f3fc',
		':older_woman_tone3:' => '1f475-1f3fd',
		':older_woman_tone4:' => '1f475-1f3fe',
		':older_woman_tone5:' => '1f475-1f3ff',
		':man_with_gua_pi_mao_tone1:' => '1f472-1f3fb',
		':man_with_gua_pi_mao_tone2:' => '1f472-1f3fc',
		':man_with_gua_pi_mao_tone3:' => '1f472-1f3fd',
		':man_with_gua_pi_mao_tone4:' => '1f472-1f3fe',
		':man_with_gua_pi_mao_tone5:' => '1f472-1f3ff',
		':man_with_turban_tone1:' => '1f473-1f3fb',
		':man_with_turban_tone2:' => '1f473-1f3fc',
		':man_with_turban_tone3:' => '1f473-1f3fd',
		':man_with_turban_tone4:' => '1f473-1f3fe',
		':man_with_turban_tone5:' => '1f473-1f3ff',
		':cop_tone1:' => '1f46e-1f3fb',
		':cop_tone2:' => '1f46e-1f3fc',
		':cop_tone3:' => '1f46e-1f3fd',
		':cop_tone4:' => '1f46e-1f3fe',
		':cop_tone5:' => '1f46e-1f3ff',
		':construction_worker_tone1:' => '1f477-1f3fb',
		':construction_worker_tone2:' => '1f477-1f3fc',
		':construction_worker_tone3:' => '1f477-1f3fd',
		':construction_worker_tone4:' => '1f477-1f3fe',
		':construction_worker_tone5:' => '1f477-1f3ff',
		':guardsman_tone1:' => '1f482-1f3fb',
		':guardsman_tone2:' => '1f482-1f3fc',
		':guardsman_tone3:' => '1f482-1f3fd',
		':guardsman_tone4:' => '1f482-1f3fe',
		':guardsman_tone5:' => '1f482-1f3ff',
		':santa_tone1:' => '1f385-1f3fb',
		':santa_tone2:' => '1f385-1f3fc',
		':santa_tone3:' => '1f385-1f3fd',
		':santa_tone4:' => '1f385-1f3fe',
		':santa_tone5:' => '1f385-1f3ff',
		':angel_tone1:' => '1f47c-1f3fb',
		':angel_tone2:' => '1f47c-1f3fc',
		':angel_tone3:' => '1f47c-1f3fd',
		':angel_tone4:' => '1f47c-1f3fe',
		':angel_tone5:' => '1f47c-1f3ff',
		':princess_tone1:' => '1f478-1f3fb',
		':princess_tone2:' => '1f478-1f3fc',
		':princess_tone3:' => '1f478-1f3fd',
		':princess_tone4:' => '1f478-1f3fe',
		':princess_tone5:' => '1f478-1f3ff',
		':bride_with_veil_tone1:' => '1f470-1f3fb',
		':bride_with_veil_tone2:' => '1f470-1f3fc',
		':bride_with_veil_tone3:' => '1f470-1f3fd',
		':bride_with_veil_tone4:' => '1f470-1f3fe',
		':bride_with_veil_tone5:' => '1f470-1f3ff',
		':walking_tone1:' => '1f6b6-1f3fb',
		':walking_tone2:' => '1f6b6-1f3fc',
		':walking_tone3:' => '1f6b6-1f3fd',
		':walking_tone4:' => '1f6b6-1f3fe',
		':walking_tone5:' => '1f6b6-1f3ff',
		':runner_tone1:' => '1f3c3-1f3fb',
		':runner_tone2:' => '1f3c3-1f3fc',
		':runner_tone3:' => '1f3c3-1f3fd',
		':runner_tone4:' => '1f3c3-1f3fe',
		':runner_tone5:' => '1f3c3-1f3ff',
		':dancer_tone1:' => '1f483-1f3fb',
		':dancer_tone2:' => '1f483-1f3fc',
		':dancer_tone3:' => '1f483-1f3fd',
		':dancer_tone4:' => '1f483-1f3fe',
		':dancer_tone5:' => '1f483-1f3ff',
		':bow_tone1:' => '1f647-1f3fb',
		':bow_tone2:' => '1f647-1f3fc',
		':bow_tone3:' => '1f647-1f3fd',
		':bow_tone4:' => '1f647-1f3fe',
		':bow_tone5:' => '1f647-1f3ff',
		':information_desk_person_tone1:' => '1f481-1f3fb',
		':information_desk_person_tone2:' => '1f481-1f3fc',
		':information_desk_person_tone3:' => '1f481-1f3fd',
		':information_desk_person_tone4:' => '1f481-1f3fe',
		':information_desk_person_tone5:' => '1f481-1f3ff',
		':no_good_tone1:' => '1f645-1f3fb',
		':no_good_tone2:' => '1f645-1f3fc',
		':no_good_tone3:' => '1f645-1f3fd',
		':no_good_tone4:' => '1f645-1f3fe',
		':no_good_tone5:' => '1f645-1f3ff',
		':ok_woman_tone1:' => '1f646-1f3fb',
		':ok_woman_tone2:' => '1f646-1f3fc',
		':ok_woman_tone3:' => '1f646-1f3fd',
		':ok_woman_tone4:' => '1f646-1f3fe',
		':ok_woman_tone5:' => '1f646-1f3ff',
		':raising_hand_tone1:' => '1f64b-1f3fb',
		':raising_hand_tone2:' => '1f64b-1f3fc',
		':raising_hand_tone3:' => '1f64b-1f3fd',
		':raising_hand_tone4:' => '1f64b-1f3fe',
		':raising_hand_tone5:' => '1f64b-1f3ff',
		':person_with_pouting_face_tone1:' => '1f64e-1f3fb',
		':person_with_pouting_face_tone2:' => '1f64e-1f3fc',
		':person_with_pouting_face_tone3:' => '1f64e-1f3fd',
		':person_with_pouting_face_tone4:' => '1f64e-1f3fe',
		':person_with_pouting_face_tone5:' => '1f64e-1f3ff',
		':person_frowning_tone1:' => '1f64d-1f3fb',
		':person_frowning_tone2:' => '1f64d-1f3fc',
		':person_frowning_tone3:' => '1f64d-1f3fd',
		':person_frowning_tone4:' => '1f64d-1f3fe',
		':person_frowning_tone5:' => '1f64d-1f3ff',
		':haircut_tone1:' => '1f487-1f3fb',
		':haircut_tone2:' => '1f487-1f3fc',
		':haircut_tone3:' => '1f487-1f3fd',
		':haircut_tone4:' => '1f487-1f3fe',
		':haircut_tone5:' => '1f487-1f3ff',
		':massage_tone1:' => '1f486-1f3fb',
		':massage_tone2:' => '1f486-1f3fc',
		':massage_tone3:' => '1f486-1f3fd',
		':massage_tone4:' => '1f486-1f3fe',
		':massage_tone5:' => '1f486-1f3ff',
		':spy_tone1:' => '1f575-1f3fb',
		':spy_tone2:' => '1f575-1f3fc',
		':spy_tone3:' => '1f575-1f3fd',
		':spy_tone4:' => '1f575-1f3fe',
		':spy_tone5:' => '1f575-1f3ff',
		':prince_tone1:' => '1f934-1f3fb',
		':prince_tone2:' => '1f934-1f3fc',
		':prince_tone3:' => '1f934-1f3fd',
		':prince_tone4:' => '1f934-1f3fe',
		':prince_tone5:' => '1f934-1f3ff',
		':mrs_claus_tone1:' => '1f936-1f3fb',
		':mrs_claus_tone2:' => '1f936-1f3fc',
		':mrs_claus_tone3:' => '1f936-1f3fd',
		':mrs_claus_tone4:' => '1f936-1f3fe',
		':mrs_claus_tone5:' => '1f936-1f3ff',
		':man_in_tuxedo_tone1:' => '1f935-1f3fb',
		':man_in_tuxedo_tone2:' => '1f935-1f3fc',
		':man_in_tuxedo_tone3:' => '1f935-1f3fd',
		':man_in_tuxedo_tone4:' => '1f935-1f3fe',
		':man_in_tuxedo_tone5:' => '1f935-1f3ff',
		':shrug_tone1:' => '1f937-1f3fb',
		':shrug_tone2:' => '1f937-1f3fc',
		':shrug_tone3:' => '1f937-1f3fd',
		':shrug_tone4:' => '1f937-1f3fe',
		':shrug_tone5:' => '1f937-1f3ff',
		':face_palm_tone1:' => '1f926-1f3fb',
		':face_palm_tone2:' => '1f926-1f3fc',
		':face_palm_tone3:' => '1f926-1f3fd',
		':face_palm_tone4:' => '1f926-1f3fe',
		':face_palm_tone5:' => '1f926-1f3ff',
		':pregnant_woman_tone1:' => '1f930-1f3fb',
		':pregnant_woman_tone2:' => '1f930-1f3fc',
		':pregnant_woman_tone3:' => '1f930-1f3fd',
		':pregnant_woman_tone4:' => '1f930-1f3fe',
		':pregnant_woman_tone5:' => '1f930-1f3ff',
		':selfie_tone1:' => '1f933-1f3fb',
		':selfie_tone2:' => '1f933-1f3fc',
		':selfie_tone3:' => '1f933-1f3fd',
		':selfie_tone4:' => '1f933-1f3fe',
		':selfie_tone5:' => '1f933-1f3ff',
		':fingers_crossed_tone1:' => '1f91e-1f3fb',
		':fingers_crossed_tone2:' => '1f91e-1f3fc',
		':fingers_crossed_tone3:' => '1f91e-1f3fd',
		':fingers_crossed_tone4:' => '1f91e-1f3fe',
		':fingers_crossed_tone5:' => '1f91e-1f3ff',
		':call_me_tone1:' => '1f919-1f3fb',
		':call_me_tone2:' => '1f919-1f3fc',
		':call_me_tone3:' => '1f919-1f3fd',
		':call_me_tone4:' => '1f919-1f3fe',
		':call_me_tone5:' => '1f919-1f3ff',
		':left_facing_fist_tone1:' => '1f91b-1f3fb',
		':left_facing_fist_tone2:' => '1f91b-1f3fc',
		':left_facing_fist_tone3:' => '1f91b-1f3fd',
		':left_facing_fist_tone4:' => '1f91b-1f3fe',
		':left_facing_fist_tone5:' => '1f91b-1f3ff',
		':right_facing_fist_tone1:' => '1f91c-1f3fb',
		':right_facing_fist_tone2:' => '1f91c-1f3fc',
		':right_facing_fist_tone3:' => '1f91c-1f3fd',
		':right_facing_fist_tone4:' => '1f91c-1f3fe',
		':right_facing_fist_tone5:' => '1f91c-1f3ff',
		':raised_back_of_hand_tone1:' => '1f91a-1f3fb',
		':raised_back_of_hand_tone2:' => '1f91a-1f3fc',
		':raised_back_of_hand_tone3:' => '1f91a-1f3fd',
		':raised_back_of_hand_tone4:' => '1f91a-1f3fe',
		':raised_back_of_hand_tone5:' => '1f91a-1f3ff',
		':handshake_tone1:' => '1f91d-1f3fb',
		':handshake_tone2:' => '1f91d-1f3fc',
		':handshake_tone3:' => '1f91d-1f3fd',
		':handshake_tone4:' => '1f91d-1f3fe',
		':handshake_tone5:' => '1f91d-1f3ff',
		':cowboy:' => '1f920',
		':clown:' => '1f921',
		':nauseated_face:' => '1f922',
		':rofl:' => '1f923',
		':drooling_face:' => '1f924',
		':lying_face:' => '1f925',
		':sneezing_face:' => '1f927',
		':prince:' => '1f934',
		':man_in_tuxedo:' => '1f935',
		':mrs_claus:' => '1f936',
		':face_palm:' => '1f926',
		':shrug:' => '1f937',
		':pregnant_woman:' => '1f930',
		':selfie:' => '1f933',
		':man_dancing:' => '1f57a',
		':call_me:' => '1f919',
		':raised_back_of_hand:' => '1f91a',
		':left_facing_fist:' => '1f91b',
		':right_facing_fist:' => '1f91c',
		':handshake:' => '1f91d',
		':fingers_crossed:' => '1f91e'
    );

	if ($shortname == "shortnames") {
		foreach ($emoji AS $key => $code) {
			$return[] = $key;
		}
	} else if ($shortname == null) {
		$return = $emoji;
	} else {
		$return = $emoji[$shortname];
	}

	return $return;

}

/*SCRIPT FOR RIPPING EMOJIS FROM emoji.json
echo '<script>var jsone = jQuery.getJSON("/emoji/emoji.json", function(data) {

		//jQuery("body").append("<img src=\'/emoji/png/"+data.grinning.unicode+".png\'>");
  for (var key in data){
	  if (data[key].category == "people") {
        var attrName = key;
        var attrValue = data[key];
		//console.log(attrName+" "+attrValue+"<br>");
		jQuery("body").append("\'"+data[key].shortname+"\' => \'"+data[key].unicode+"\', <br>");
	  }
  }

});</script>';*/
