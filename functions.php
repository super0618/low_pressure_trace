<?php
function import_db($graph_date, $processID, $shift)
{
    global $db, $tblCastProcesses, $tblCastData;

    $query = "SELECT * FROM {$tblCastProcesses} WHERE id = {$processID}";
    $result = $db->query($query);
    $row = mysqli_fetch_object($result);
    $process = $row->process_name;

    //Get file
    $path = "/cast_trace/data/" . $process . "/" . date('ymd', strtotime($graph_date)) . str_replace("shift", "", $shift) . ".csv";
    $full_path = $_SERVER['DOCUMENT_ROOT'] . $path;

    $i = 0;

    return $full_path;
    $import = "";
    if (file_exists($full_path)) {
        $file = fopen($full_path, "r");

        if ($file) {
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                if ($i > 3) {
                    //Check to exist data
                    $query = "SELECT * FROM {$tblCastData} WHERE shot_date = '{$graph_date}' AND shot_time = '{$getData[2]}'";
                    $res = $db->query($query);
                    $num = mysqli_num_rows($res);
                    if ($num == 0) {
                        //$shotDates = explode("/", $getData[1]);
                        //$shotDate = $shotDates[2]."-".$shotDates[1]."-".$shotDates[0];

                        $created_at = $graph_date . " " . $getData[2];

                        for ($i = 2; $i < 230; $i++) {
                            if (empty($getData[$i]))
                                $getData[$i] = 0;
                        }

                        $import = "INSERT INTO {$tblCastData} (process_id, id_no, shot_date, shot_time, shot_no, lot_no, file_name, general_die_no, product_type, unique_die_no, throwing, spare1, spare2, 
                      set_metal_press, set_inten_valve_rate, set_profile_position1, set_profile_velocity1, set_profile_position2, set_profile_velocity2, set_profile_position3, set_profile_velocity3,
                      set_profile_position4, set_profile_velocity4, set_profile_position5, set_profile_velocity5, set_profile_position6, set_profile_velocity6, set_profile_position7, set_profile_velocity7,
                      set_profile_position8, set_profile_velocity8, set_profile_position9, set_profile_velocity9, set_profile_position10, set_profile_velocity10, metal_press, inten_time, biscuit, shot_vel1,
                      shot_vel2, shot_vel3, shot_vel4, projection_low_speed, projection_medium_speed, projection_high_speed, projection_medium_speed_start_stroke, projection_high_speed_start_stroke, projection_rise_time_medium_speed, 
                      fast_shot_accel_time, max_shot_vel, fast_shot_start_point, fast_shot_speed, metal_volume, projection_low_speed_rate_of_variability, projection_low_speed_acceleration_time, fast_shot_stroke, 
                      injection_pressure_back_pressure_peak_pressure, injection_pressure_back_pressure_arrival_time, casting_pressure_speed_acc1, casting_pressure_increment_acc2, cycle_time, insert_time, 
                      die_close, core_nsert_time, ladle_time, shot_time_sec, die_time, die_open_time, core_return_time, eject_time, extractor_time, die_spray_time, die_temp_at_pouring, die_temp_at_spray, 
                      hydraulic_oil_temp, molten_metal_temp, moving_die_temp1, moving_die_temp2, movable_mold_temperature, movable_mold_temperature4, fixed_die_temp1, fixed_die_temp2, fixation_mold_temperature3,
                      fixation_mold_temperature4, sleeve_temperature, product_refrigeration_water_temperature, water_inlet_temp, movable_hyperbaric_pressure1, movable_hyperbaric_pressure2, movable_hyperbaric_pressure3, 
                      movable_hyperbaric_pressure4, movable_hyperbaric_pressure5, movable_hyperbaric_pressure6, movable_normal_pressure1, movable_normal_pressure2, movable_normal_pressure3, fixation_hyperbaric_pressure1,
                      fixation_normal_pressures1, water_out_temp_mp_hp1, water_out_temp_mp_hp2, water_out_temp_mp_hp3, water_out_temp_mp_hp4, water_out_temp_mp_hp5, water_out_temp_mp_hp6, condition_coming_off, 
                      water_out_temp_mp_lp1, water_out_temp_mp_lp2, movable_normal_pressure3_exit_temperature, water_out_temp_sp_hp1, water_out_temp_sp_lp2, water_out_temp_sp_lp1, fixation_normal_pressures2,
                      fixation_normal_pressures3, movable_hyperbaric_pressure1_entrance_flow, movable_hyperbaric_pressure2_entrance_flow, movable_hyperbaric_pressure3_entrance_flow, movable_hyperbaric_pressure4_entrance_flow,
                      movable_hyperbaric_pressure5_entrance_flow, movable_hyperbaric_pressure6_entrance_flow, movable_normal_pressure1_entrance_flow, movable_normal_pressure2_entrance_flow, movable_normal_pressure3_entrance_flow,
                      fixation_hyperbaric_pressure1_entrance_flow, fixation_hyperbaric_pressure2_entrance_flow, water_out_flow_mp_hp1, water_out_flow_mp_hp2, water_out_flow_mp_hp3, water_out_flow_mp_hp4, 
                      water_out_flow_mp_hp5, water_out_flow_mp_hp6, spray_no, water_out_flow_mp_lp1, water_out_flow_mp_lp2, movable_normal_pressure3_exit_flow, water_out_flow_sp_hp1, water_out_flow_sp_lp2, 
                      water_out_flow_sp_lp1, fixation_normal_pressures2_exit_flow, fixation_normal_pressures3_exit_flow, movable_hyperbaric_pressure1_entrance_pressure, movable_hyperbaric_pressure2_entrance_pressure,
                      movable_hyperbaric_pressure3_entrance_pressure, movable_normal_pressure1_entrance_pressure, fixation_hyperbaric_pressure1_entrance_pressure, fixation_hyperbaric_pressure2_entrance_pressure, 
                      movable_normal_pressure2_entrance_pressure, movable_hyperbaric_pressure1_exit_pressure, movable_hyperbaric_pressure2_exit_pressure, movable_hyperbaric_pressure3_exit_pressure, 
                      movable_hyperbaric_pressure4_exit_pressure, movable_hyperbaric_pressure5_exit_pressure, movable_hyperbaric_pressure6_exit_pressure, movable_hyperbaric_pressure7_exit_pressure, 
                      movable_normal_pressure1_exit_pressure, movable_normal_pressure2_exit_pressure, movable_normal_pressure3_exit_pressure, fixation_hyperbaric_pressure1_exit_pressure, 
                      fixation_hyperbaric_pressure2_exit_pressure, movable_outside_cold_water, fixation_outside_cold_water, spray_a1_flow, spray_a2_flow, spray_a3_flow, spray_a4_flow, spare3, core_blowing_spray, 
                      mold_lubricant_flow_quantity_survey_mobile, mold_lubricant_flow_quantity_survey_fixation, mold_lubricant_flow_quantity_survey, mold_lubricant_pressure_mobile, mold_lubricant_pressure_fixation, 
                      mold_lubricant_pressure, air_blow_pressure1_mobile, air_blow_pressure1_fixation, air_blow_pressure1, air_blow_pressure2_mobile, air_blow_pressure2_fixation, air_blow_pressure2, die_vac_press_sp_rs, 
                      die_vac_press_sp_ls, vac_tank_press, line_vac_press_sp_rs, line_vac_press_sp_ls, air_blow_press_sp_rs, air_blow_press_sp_ls, decompression_grade1, decompression_grade2, decompression_grade3, 
                      decompression_grade4, rsv1_clsed_time_after_projection_start, rsv2_clsed_time_after_projection_start, locking_force, clamp_capacity_variation, tip_cooling_water_vol, sleeve_cooling_water_vol, 
                      spray_value_mov_oily_lub, spray_value_fix_oily_lub, shot_axial_force_up, shot_axial_force_down, allotrio_det_thick_dw, allotrio_det_thin_up, allotrio_det_thin_dw, die_close_fin_pos_up, 
                      die_close_fin_pos_dw, clamping_start, shot_start, cavity_gas_pressure_peak_value, air_blow_average_pressure, air_stream_flow, movable_core_activated_oil_pressure_max, ejector_head_press, 
                      pin_displacement_waveform_peak_value_1, pin_displacement_waveform_peak_arrival_time1, pin_displacement_waveform_peak_value_2, pin_displacement_waveform_peak_arrival_time2, 
                      pin_displacement_waveform_peak_value_3, pin_displacement_waveform_peak_arrival_time3, pin_displacement_waveform_peak_value_4, pin_displacement_waveform_peak_arrival_time4,
                      pin_displacement_waveform_peak_value_5, pin_displacement_waveform_peak_arrival_time5, pin_displacement_waveform_peak_value_6, pin_displacement_waveform_peak_arrival_time6,
                      pin_displacement_waveform_peak_value_7, pin_displacement_waveform_peak_arrival_time7, pin_displacement_waveform_peak_value_8, pin_displacement_waveform_peak_arrival_time8, 
                      pin_displacement_waveform_peak_value_9, pin_displacement_waveform_peak_arrival_time9, pin_displacement_waveform_peak_value_10, pin_displacement_waveform_peak_arrival_time10, LineName, created_at)
                      VALUES ({$processID}, '{$getData[0]}','{$graph_date}','{$getData[2]}','{$getData[3]}','{$getData[4]}','{$getData[5]}','{$getData[6]}','{$getData[7]}','{$getData[8]}','{$getData[9]}','{$getData[10]}','{$getData[11]}'
                      ,'{$getData[12]}','{$getData[13]}','{$getData[14]}','{$getData[15]}','{$getData[16]}','{$getData[17]}','{$getData[18]}','{$getData[19]}','{$getData[20]}','{$getData[21]}','{$getData[22]}','{$getData[23]}'
                      ,'{$getData[24]}','{$getData[25]}','{$getData[26]}','{$getData[27]}','{$getData[28]}','{$getData[29]}','{$getData[30]}','{$getData[31]}','{$getData[32]}','{$getData[33]}','{$getData[34]}','{$getData[35]}'
                      ,'{$getData[36]}','{$getData[37]}','{$getData[38]}','{$getData[39]}','{$getData[40]}','{$getData[41]}','{$getData[42]}','{$getData[43]}','{$getData[44]}','{$getData[45]}','{$getData[46]}','{$getData[47]}'
                      ,'{$getData[48]}','{$getData[49]}','{$getData[50]}','{$getData[51]}','{$getData[52]}','{$getData[53]}','{$getData[54]}','{$getData[55]}','{$getData[56]}','{$getData[57]}','{$getData[58]}','{$getData[59]}'
                      ,'{$getData[60]}','{$getData[61]}','{$getData[62]}','{$getData[63]}','{$getData[64]}','{$getData[65]}','{$getData[66]}','{$getData[67]}','{$getData[68]}','{$getData[69]}','{$getData[70]}','{$getData[71]}'
                      ,'{$getData[72]}','{$getData[73]}','{$getData[74]}','{$getData[75]}','{$getData[76]}','{$getData[77]}','{$getData[78]}','{$getData[79]}','{$getData[80]}','{$getData[81]}','{$getData[82]}','{$getData[83]}'
                      ,'{$getData[84]}','{$getData[85]}','{$getData[86]}','{$getData[87]}','{$getData[88]}','{$getData[89]}','{$getData[90]}','{$getData[91]}','{$getData[92]}','{$getData[93]}','{$getData[94]}','{$getData[95]}'
                      ,'{$getData[96]}','{$getData[97]}','{$getData[98]}','{$getData[99]}','{$getData[100]}','{$getData[101]}','{$getData[102]}','{$getData[103]}','{$getData[104]}','{$getData[105]}','{$getData[106]}','{$getData[107]}'
                      ,'{$getData[108]}','{$getData[109]}','{$getData[110]}','{$getData[111]}','{$getData[112]}','{$getData[113]}','{$getData[114]}','{$getData[115]}','{$getData[116]}','{$getData[117]}','{$getData[118]}','{$getData[119]}'
                      ,'{$getData[120]}','{$getData[121]}','{$getData[122]}','{$getData[123]}','{$getData[124]}','{$getData[125]}','{$getData[126]}','{$getData[127]}','{$getData[128]}','{$getData[129]}'
                      ,'{$getData[130]}','{$getData[131]}','{$getData[132]}','{$getData[133]}','{$getData[134]}','{$getData[135]}','{$getData[136]}','{$getData[137]}','{$getData[138]}','{$getData[139]}'
                      ,'{$getData[140]}','{$getData[141]}','{$getData[142]}','{$getData[143]}','{$getData[144]}','{$getData[145]}','{$getData[146]}','{$getData[147]}','{$getData[148]}','{$getData[149]}'
                      ,'{$getData[150]}','{$getData[151]}','{$getData[152]}','{$getData[153]}','{$getData[154]}','{$getData[155]}','{$getData[156]}','{$getData[157]}','{$getData[158]}','{$getData[159]}'
                      ,'{$getData[160]}','{$getData[161]}','{$getData[162]}','{$getData[163]}','{$getData[164]}','{$getData[165]}','{$getData[166]}','{$getData[167]}','{$getData[168]}','{$getData[169]}'
                      ,'{$getData[170]}','{$getData[171]}','{$getData[172]}','{$getData[173]}','{$getData[174]}','{$getData[175]}','{$getData[176]}','{$getData[177]}','{$getData[178]}','{$getData[179]}'
                      ,'{$getData[180]}','{$getData[181]}','{$getData[182]}','{$getData[183]}','{$getData[184]}','{$getData[185]}','{$getData[186]}','{$getData[187]}','{$getData[188]}','{$getData[189]}'
                      ,'{$getData[190]}','{$getData[191]}','{$getData[192]}','{$getData[193]}','{$getData[194]}','{$getData[195]}','{$getData[196]}','{$getData[197]}','{$getData[198]}','{$getData[199]}'
                      ,'{$getData[200]}','{$getData[201]}','{$getData[202]}','{$getData[203]}','{$getData[204]}','{$getData[205]}','{$getData[206]}','{$getData[207]}','{$getData[208]}','{$getData[209]}'
                      ,'{$getData[210]}','{$getData[211]}','{$getData[212]}','{$getData[213]}','{$getData[214]}','{$getData[215]}','{$getData[216]}','{$getData[217]}','{$getData[218]}','{$getData[219]}'
                      ,'{$getData[220]}','{$getData[221]}','{$getData[222]}','{$getData[223]}','{$getData[224]}','{$getData[225]}','{$getData[226]}','{$getData[227]}','{$getData[228]}','{$getData[229]}'
                      ,'{$getData[230]}', '{$created_at}')";
                        $db->query($import);
                    }
                }
                $i++;
            }
        }
    }

    return $import;
}

function import_db_cron($graph_date, $path, $processID, $shift)
{
    global $db, $DB_NAME, $tblCastProcesses, $tblCastData;

    $query = "SELECT * FROM {$tblCastProcesses} WHERE id = {$processID}";
    $result = $db->query($query);
    $row = mysqli_fetch_object($result);
    $process = $row->process_name;
    $shiftId = str_replace("shift", "", $shift);

    //Get file
    //$path = "/cast_trace/data/".$process."/".date('ymd', strtotime($graph_date)).str_replace("shift","",$shift).".csv";
    //$path = "data/".$process."/".date('ymd', strtotime($graph_date)).str_replace("shift","",$shift).".csv";

    //$full_path = $_SERVER['DOCUMENT_ROOT'].'/hptrace/'.$path;
    $full_path = $_SERVER['DOCUMENT_ROOT'] . '/hptrace/' . $path;

    $i = 0;

    // return $full_path;
    $import = "";

    if (file_exists($full_path)) {

        $file = fopen($full_path, "r");

        echo '<pre>';
        if ($file) {

            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {

                if ($i > 3) {

                    //Check to exist data
                    $query = "SELECT * FROM {$tblCastData} WHERE shot_date = '{$graph_date}' AND shot_time = '{$getData[2]}'";
                    $res = $db->query($query);
                    $num = mysqli_num_rows($res);
                    if ($num == 0) {
                        echo '145';
                        //$shotDates = explode("/", $getData[1]);
                        //$shotDate = $shotDates[2]."-".$shotDates[1]."-".$shotDates[0];

                        $created_at = date('Y-m-d', strtotime($graph_date)) . " " . $getData[1];

                        for ($i = 2; $i < 230; $i++) {
                            if (empty($getData[$i]))
                                $getData[$i] = 0;
                        }
                        //print_r($getData);
                        //die();
                        $shot_date = date('Y-m-d', strtotime($getData[0]));

                        $import = "INSERT INTO " . $DB_NAME . "." . $tblCastData . " (process_id, id_no, shot_date, shot_time, shot_no, file_name, general_die_no, product_type, unique_die_no, throwing, spare1, spare2, 
                      set_metal_press, set_inten_valve_rate, set_profile_position1, set_profile_velocity1, set_profile_position2, set_profile_velocity2, set_profile_position3, set_profile_velocity3,
                      set_profile_position4, set_profile_velocity4, set_profile_position5, set_profile_velocity5, set_profile_position6, set_profile_velocity6, set_profile_position7, set_profile_velocity7,
                      set_profile_position8, set_profile_velocity8, set_profile_position9, set_profile_velocity9, set_profile_position10, set_profile_velocity10, metal_press, inten_time, biscuit, shot_vel1,
                      shot_vel2, shot_vel3, shot_vel4, projection_low_speed, projection_medium_speed, projection_high_speed, projection_medium_speed_start_stroke, projection_high_speed_start_stroke, projection_rise_time_medium_speed, 
                      fast_shot_accel_time, max_shot_vel, fast_shot_start_point, fast_shot_speed, metal_volume, projection_low_speed_rate_of_variability, projection_low_speed_acceleration_time, fast_shot_stroke, 
                      injection_pressure_back_pressure_peak_pressure, injection_pressure_back_pressure_arrival_time, casting_pressure_speed_acc1, casting_pressure_increment_acc2, cycle_time, insert_time, 
                      die_close, core_nsert_time, ladle_time, shot_time_sec, die_time, die_open_time, core_return_time, eject_time, extractor_time, die_spray_time, die_temp_at_pouring, die_temp_at_spray, 
                      hydraulic_oil_temp, molten_metal_temp, moving_die_temp1, moving_die_temp2, movable_mold_temperature, movable_mold_temperature4, fixed_die_temp1, fixed_die_temp2, fixation_mold_temperature3,
                      fixation_mold_temperature4, sleeve_temperature, product_refrigeration_water_temperature, water_inlet_temp, movable_hyperbaric_pressure1, movable_hyperbaric_pressure2, movable_hyperbaric_pressure3, 
                      movable_hyperbaric_pressure4, movable_hyperbaric_pressure5, movable_hyperbaric_pressure6, movable_normal_pressure1, movable_normal_pressure2, movable_normal_pressure3, fixation_hyperbaric_pressure1,
                      fixation_normal_pressures1, water_out_temp_mp_hp1, water_out_temp_mp_hp2, water_out_temp_mp_hp3, water_out_temp_mp_hp4, water_out_temp_mp_hp5, water_out_temp_mp_hp6, condition_coming_off, 
                      water_out_temp_mp_lp1, water_out_temp_mp_lp2, movable_normal_pressure3_exit_temperature, water_out_temp_sp_hp1, water_out_temp_sp_lp2, water_out_temp_sp_lp1, fixation_normal_pressures2,
                      fixation_normal_pressures3, movable_hyperbaric_pressure1_entrance_flow, movable_hyperbaric_pressure2_entrance_flow, movable_hyperbaric_pressure3_entrance_flow, movable_hyperbaric_pressure4_entrance_flow,
                      movable_hyperbaric_pressure5_entrance_flow, movable_hyperbaric_pressure6_entrance_flow, movable_normal_pressure1_entrance_flow, movable_normal_pressure2_entrance_flow, movable_normal_pressure3_entrance_flow,
                      fixation_hyperbaric_pressure1_entrance_flow, fixation_hyperbaric_pressure2_entrance_flow, water_out_flow_mp_hp1, water_out_flow_mp_hp2, water_out_flow_mp_hp3, water_out_flow_mp_hp4, 
                      water_out_flow_mp_hp5, water_out_flow_mp_hp6, spray_no, water_out_flow_mp_lp1, water_out_flow_mp_lp2, movable_normal_pressure3_exit_flow, water_out_flow_sp_hp1, water_out_flow_sp_lp2, 
                      water_out_flow_sp_lp1, fixation_normal_pressures2_exit_flow, fixation_normal_pressures3_exit_flow, movable_hyperbaric_pressure1_entrance_pressure, movable_hyperbaric_pressure2_entrance_pressure,
                      movable_hyperbaric_pressure3_entrance_pressure, movable_normal_pressure1_entrance_pressure, fixation_hyperbaric_pressure1_entrance_pressure, fixation_hyperbaric_pressure2_entrance_pressure, 
                      movable_normal_pressure2_entrance_pressure, movable_hyperbaric_pressure1_exit_pressure, movable_hyperbaric_pressure2_exit_pressure, movable_hyperbaric_pressure3_exit_pressure, 
                      movable_hyperbaric_pressure4_exit_pressure, movable_hyperbaric_pressure5_exit_pressure, movable_hyperbaric_pressure6_exit_pressure, movable_hyperbaric_pressure7_exit_pressure, 
                      movable_normal_pressure1_exit_pressure, movable_normal_pressure2_exit_pressure, movable_normal_pressure3_exit_pressure, fixation_hyperbaric_pressure1_exit_pressure, 
                      fixation_hyperbaric_pressure2_exit_pressure, movable_outside_cold_water, fixation_outside_cold_water, spray_a1_flow, spray_a2_flow, spray_a3_flow, spray_a4_flow, spare3, core_blowing_spray, 
                      mold_lubricant_flow_quantity_survey_mobile, mold_lubricant_flow_quantity_survey_fixation, mold_lubricant_flow_quantity_survey, mold_lubricant_pressure_mobile, mold_lubricant_pressure_fixation, 
                      mold_lubricant_pressure, air_blow_pressure1_mobile, air_blow_pressure1_fixation, air_blow_pressure1, air_blow_pressure2_mobile, air_blow_pressure2_fixation, air_blow_pressure2, die_vac_press_sp_rs, 
                      die_vac_press_sp_ls, vac_tank_press, line_vac_press_sp_rs, line_vac_press_sp_ls, air_blow_press_sp_rs, air_blow_press_sp_ls, decompression_grade1, decompression_grade2, decompression_grade3, 
                      decompression_grade4, rsv1_clsed_time_after_projection_start, rsv2_clsed_time_after_projection_start, locking_force, clamp_capacity_variation, tip_cooling_water_vol, sleeve_cooling_water_vol, 
                      spray_value_mov_oily_lub, spray_value_fix_oily_lub, shot_axial_force_up, shot_axial_force_down, allotrio_det_thick_dw, allotrio_det_thin_up, allotrio_det_thin_dw, die_close_fin_pos_up, 
                      die_close_fin_pos_dw, clamping_start, shot_start, cavity_gas_pressure_peak_value, air_blow_average_pressure, air_stream_flow, movable_core_activated_oil_pressure_max, ejector_head_press, 
                      pin_displacement_waveform_peak_value_1, pin_displacement_waveform_peak_arrival_time1, pin_displacement_waveform_peak_value_2, pin_displacement_waveform_peak_arrival_time2, 
                      pin_displacement_waveform_peak_value_3, pin_displacement_waveform_peak_arrival_time3, pin_displacement_waveform_peak_value_4, pin_displacement_waveform_peak_arrival_time4,
                      pin_displacement_waveform_peak_value_5, pin_displacement_waveform_peak_arrival_time5, pin_displacement_waveform_peak_value_6, pin_displacement_waveform_peak_arrival_time6,
                      pin_displacement_waveform_peak_value_7, pin_displacement_waveform_peak_arrival_time7, pin_displacement_waveform_peak_value_8, pin_displacement_waveform_peak_arrival_time8, 
                      pin_displacement_waveform_peak_value_9, pin_displacement_waveform_peak_arrival_time9, pin_displacement_waveform_peak_value_10, pin_displacement_waveform_peak_arrival_time10, LineName,shift, created_at)
                      VALUES ({$processID}, '{$getData[2]}','{$shot_date}','{$getData[1]}','{$getData[3]}','{$getData[5]}','{$getData[6]}','{$getData[7]}','{$getData[8]}','{$getData[9]}','{$getData[10]}','{$getData[11]}'
                      ,'{$getData[12]}','{$getData[13]}','{$getData[14]}','{$getData[15]}','{$getData[16]}','{$getData[17]}','{$getData[18]}','{$getData[19]}','{$getData[20]}','{$getData[21]}','{$getData[22]}','{$getData[23]}'
                      ,'{$getData[24]}','{$getData[25]}','{$getData[26]}','{$getData[27]}','{$getData[28]}','{$getData[29]}','{$getData[30]}','{$getData[31]}','{$getData[32]}','{$getData[33]}','{$getData[34]}','{$getData[35]}'
                      ,'{$getData[36]}','{$getData[37]}','{$getData[38]}','{$getData[39]}','{$getData[40]}','{$getData[41]}','{$getData[42]}','{$getData[43]}','{$getData[44]}','{$getData[45]}','{$getData[46]}','{$getData[47]}'
                      ,'{$getData[48]}','{$getData[49]}','{$getData[50]}','{$getData[51]}','{$getData[52]}','{$getData[53]}','{$getData[54]}','{$getData[55]}','{$getData[56]}','{$getData[57]}','{$getData[58]}','{$getData[59]}'
                      ,'{$getData[60]}','{$getData[61]}','{$getData[62]}','{$getData[63]}','{$getData[64]}','{$getData[65]}','{$getData[66]}','{$getData[67]}','{$getData[68]}','{$getData[69]}','{$getData[70]}','{$getData[71]}'
                      ,'{$getData[72]}','{$getData[73]}','{$getData[74]}','{$getData[75]}','{$getData[76]}','{$getData[77]}','{$getData[78]}','{$getData[79]}','{$getData[80]}','{$getData[81]}','{$getData[82]}','{$getData[83]}'
                      ,'{$getData[84]}','{$getData[85]}','{$getData[86]}','{$getData[87]}','{$getData[88]}','{$getData[89]}','{$getData[90]}','{$getData[91]}','{$getData[92]}','{$getData[93]}','{$getData[94]}','{$getData[95]}'
                      ,'{$getData[96]}','{$getData[97]}','{$getData[98]}','{$getData[99]}','{$getData[100]}','{$getData[101]}','{$getData[102]}','{$getData[103]}','{$getData[104]}','{$getData[105]}','{$getData[106]}','{$getData[107]}'
                      ,'{$getData[108]}','{$getData[109]}','{$getData[110]}','{$getData[111]}','{$getData[112]}','{$getData[113]}','{$getData[114]}','{$getData[115]}','{$getData[116]}','{$getData[117]}','{$getData[118]}','{$getData[119]}'
                      ,'{$getData[120]}','{$getData[121]}','{$getData[122]}','{$getData[123]}','{$getData[124]}','{$getData[125]}','{$getData[126]}','{$getData[127]}','{$getData[128]}','{$getData[129]}'
                      ,'{$getData[130]}','{$getData[131]}','{$getData[132]}','{$getData[133]}','{$getData[134]}','{$getData[135]}','{$getData[136]}','{$getData[137]}','{$getData[138]}','{$getData[139]}'
                      ,'{$getData[140]}','{$getData[141]}','{$getData[142]}','{$getData[143]}','{$getData[144]}','{$getData[145]}','{$getData[146]}','{$getData[147]}','{$getData[148]}','{$getData[149]}'
                      ,'{$getData[150]}','{$getData[151]}','{$getData[152]}','{$getData[153]}','{$getData[154]}','{$getData[155]}','{$getData[156]}','{$getData[157]}','{$getData[158]}','{$getData[159]}'
                      ,'{$getData[160]}','{$getData[161]}','{$getData[162]}','{$getData[163]}','{$getData[164]}','{$getData[165]}','{$getData[166]}','{$getData[167]}','{$getData[168]}','{$getData[169]}'
                      ,'{$getData[170]}','{$getData[171]}','{$getData[172]}','{$getData[173]}','{$getData[174]}','{$getData[175]}','{$getData[176]}','{$getData[177]}','{$getData[178]}','{$getData[179]}'
                      ,'{$getData[180]}','{$getData[181]}','{$getData[182]}','{$getData[183]}','{$getData[184]}','{$getData[185]}','{$getData[186]}','{$getData[187]}','{$getData[188]}','{$getData[189]}'
                      ,'{$getData[190]}','{$getData[191]}','{$getData[192]}','{$getData[193]}','{$getData[194]}','{$getData[195]}','{$getData[196]}','{$getData[197]}','{$getData[198]}','{$getData[199]}'
                      ,'{$getData[200]}','{$getData[201]}','{$getData[202]}','{$getData[203]}','{$getData[204]}','{$getData[205]}','{$getData[206]}','{$getData[207]}','{$getData[208]}','{$getData[209]}'
                      ,'{$getData[210]}','{$getData[211]}','{$getData[212]}','{$getData[213]}','{$getData[214]}','{$getData[215]}','{$getData[216]}','{$getData[217]}','{$getData[218]}','{$getData[219]}'
                      ,'{$getData[220]}','{$getData[221]}','{$getData[222]}','{$getData[223]}','{$getData[224]}','{$getData[225]}','{$getData[226]}','{$getData[227]}','{$getData[228]}','{$getData[229]}'
                      ,'{$getData[229]}','{$shiftId}', '{$created_at}')";

                        $db->query($import);
                        // echo '<br>'.$import.'<br>';
                        // die();
                    }
                }
                $i++;
            }
        }
    }


    echo '<br>' . $i . ' records imported successfully!';
    return $import;
}

function get_line($id)
{
    global $db, $tblLines;
    $query = "SELECT * FROM {$tblLines} WHERE id={$id}";
    $result = $db->query($query);
    $line = mysqli_fetch_object($result);
    return $line;
}

function get_tag($id)
{
    global $db, $tblTagNames;
    $query = "SELECT * FROM {$tblTagNames} WHERE id={$id}";
    $result = $db->query($query);
    $tag = mysqli_fetch_object($result);
    return $tag;
}

function get_tag_address($id)
{
    global $db, $tblTagAddress;
    $query = "SELECT * FROM {$tblTagAddress} WHERE id={$id}";
    $result = $db->query($query);
    $address = mysqli_fetch_object($result);
    return $address;
}

function get_graph_data($graph_date, $shift, $line)
{
    global $db, $tblCastDisplay, $tblLive;
    $time_set = get_start_end_time($graph_date, $shift);
    $start_time = $time_set['start'];
    $end_time = $time_set['end'];

    $g_data = array();


    $query = "SELECT * FROM {$tblCastDisplay}";
    $result = $db->query($query);
    while ($row = mysqli_fetch_object($result)) {

        if ($row->tag_address != null && $row->tag_address != "") {

            $sql = "SELECT * FROM {$tblLive} WHERE timestamp >= '{$start_time}' AND timestamp <= '{$end_time}' AND name = '{$row->tag_address}' ORDER BY timestamp DESC limit 1";
            $res = $db->query($sql);

            if ($res) {
                $machine = mysqli_fetch_object($res);
                if ($machine) {
                    if ($machine->value == 1) {
                        $g_data[$row->name] = 1;
                    } else {
                        $g_data[$row->name] = 0;
                    }
                } else {
                    $g_data[$row->name] = 1;
                }
            } else {
                $g_data[$row->name] = 1;
            }
        } else {
            $g_data[$row->name] = 1;
        }
    }

    return $g_data;
}

function get_data_table($graph_date, $shift, $line)
{
    global $db;
    $time_set = get_start_end_time($graph_date, $shift);
    $start_time = $time_set['start'];
    $end_time = $time_set['end'];

    $g_data = array();

    $line = strtolower($line);

    $query = "SELECT * FROM {$line} WHERE time >= '{$start_time}' AND time <= '{$end_time}' ORDER BY time ASC";
    $result = $db->query($query);

    if ($result) {
        while ($row = mysqli_fetch_object($result)) {
            array_push($g_data, $row);
        }
    }

    $table_id = substr($start_time, 0, 10) . "_" . $line . "_" . $shift;

    echo "<table class='table' id='" . $table_id . "'>";

    if (count($g_data) > 0) {
        echo "<thead><tr>";
        foreach ($g_data[0] as $key => $column) {
            if ($key != "id") {
                $title = strtoupper(str_replace("_", " ", $key));
                echo "<th>" . $title . "</th>";
            }
        }

        echo "</tr></thead>";
        echo '<tbody>';
        foreach ($g_data as $data) {
            echo "<tr>";
            foreach ($data as $key => $column) {
                if ($key != "id") {
                    //$title = strtoupper(str_replace("_", " ", $key));
                    if ($key == "time") {
                        $columns = explode(" ", $column);
                        $column = $columns[1];
                    }
                    echo "<td>" . $column . "</td>";
                }
            }
            echo "</tr>";
        }
        echo '<tbody>';
    } else {
        echo "<tr><td>No Data</td></tr>";
    }

    echo "</table>";
}

function get_start_end_time($date, $shift)
{
    global $shift_settings;

    $data = array();

    $shift_index = str_replace('shift', '', $shift);

    $start = $date . " " . $shift_settings[$shift_index]['start'] . ":00";
    $end = $date . " " . $shift_settings[$shift_index]['end'] . ":00";

    if (strtotime($start) > strtotime($end)) {
        $end = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($end)));
    }

    $data['start'] = $start;
    $data['end'] = $end;

    return $data;
}


function convert_date_string($date)
{
    $string = explode("-", $date);
    return $string[2] . '-' . $string[1] . '-' . $string[0];
}

function pagination($total, $per_page = 10, $page = 1, $url = '?')
{
    //$query = "SELECT COUNT(*) as `num` FROM {$query}";
    //echo $query;
    //$row = mysql_fetch_array(mysql_query($query));
    //$total = $row['num'];
    //echo $total;
    $adjacents = "2";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;

    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total / $per_page);
    $lpm1 = $lastpage - 1;

    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<ul class='pagination'>";
        //$pagination .= "<li class='details'>Page $page of $lastpage</li>";
        if ($lastpage < 20 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= "<li class='current'> <a class='current'>$counter</a> </li>";
                else {
                    if ($counter == 1) {
                        $pagination .= "<li> <a href='{$url}1'>$counter</a> </li>";
                    } else {
                        $pagination .= "<li> <a href='{$url}$counter'>$counter</a> </li>";
                    }
                }
            }
        } elseif ($lastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class='current'> <a class='current'>$counter</a> </li>";
                    else {
                        if ($counter == 1) {
                            $pagination .= "<li> <a href='{$url}1'>$counter</a> </li>";
                        } else {
                            $pagination .= "<li> <a href='{$url}$counter'>$counter</a> </li>";
                        }
                    }
                }
                $pagination .= "<li class='dot'>...</li>";
                $pagination .= "<li> <a href='{$url}$lpm1'>$lpm1</a> </li>";
                $pagination .= "<li> <a href='{$url}$lastpage'>$lastpage</a> </li>";
            } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<li> <a href='{$url}1'>1</a> </li>";
                $pagination .= "<li> <a href='{$url}2'>2</a> </li>";
                $pagination .= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class='current'> <a class='current'>$counter</a> </li>";
                    else {
                        if ($counter == 1) {
                            $pagination .= "<li> <a href='{$url}1'>$counter</a> </li>";
                        } else {
                            $pagination .= "<li> <a href='{$url}$counter'>$counter</a> </li>";
                        }
                    }
                }
                $pagination .= "<li class='dot'>..</li>";
                $pagination .= "<li> <a href='{$url}$lpm1'>$lpm1</a> </li>";
                $pagination .= "<li> <a href='{$url}$lastpage'>$lastpage</a> </li>";
            } else {
                $pagination .= "<li> <a href='{$url}1'>1</a> </li>";
                $pagination .= "<li> <a href='{$url}2'>2</a> </li>";
                $pagination .= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li> <a class='current'>$counter</a> </li>";
                    else {
                        if ($counter == 1) {
                            $pagination .= "<li> <a href='{$url}1'>$counter</a> </li>";
                        } else {
                            $pagination .= "<li> <a href='{$url}$counter'>$counter</a> </li>";
                        }
                    }
                }
            }
        }

        if ($page < $counter - 1) {
            $pagination .= "<li> <a href='{$url}$next'>Next</a> </li>";
            $pagination .= "<li> <a href='{$url}$lastpage'>Last</a> </li>";
        } else {
            $pagination .= "<li class='current'> <a class='current'>Next</a> </li>";
            $pagination .= "<li class='current'> <a class='current'>Last</a> </li>";
        }
        $pagination .= "</ul>\n";
    }


    return $pagination;
}


function pagination2($total, $per_page = 10, $page = 1, $url = '?')
{
    $adjacents = "2";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;

    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total / $per_page);
    $lpm1 = $lastpage - 1;

    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<ul class='pagination'>";
        //$pagination .= "<li class='details'>Page $page of $lastpage</li>";
        if ($lastpage < 20 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= "<li class='current'> <a class='current'>$counter</a> </li>";
                else {
                    $pagination .= "<li> <a class='go-page' data-page='{$counter}'>$counter</a> </li>";
                }
            }
        } elseif ($lastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class='current'> <a class='current'>$counter</a> </li>";
                    else {
                        $pagination .= "<li> <a class='go-page' data-page='{$counter}'>$counter</a> </li>";
                    }
                }
                $pagination .= "<li class='dot'>...</li>";
                $pagination .= "<li> <a class='go-page' data-page='{$lpm1}'>$lpm1</a> </li>";
                $pagination .= "<li> <a class='go-page' data-page='{$lastpage}'>$lastpage</a> </li>";
            } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<li> <a class='go-page' data-page='1'>1</a> </li>";
                $pagination .= "<li> <a  class='go-page' data-page='2'>2</a> </li>";
                $pagination .= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class='current'> <a class='current'>$counter</a> </li>";
                    else {
                        $pagination .= "<li> <a class='go-page' data-page='{$counter}'>$counter</a> </li>";
                    }
                }
                $pagination .= "<li class='dot'>..</li>";
                $pagination .= "<li> <a class='go-page' data-page='{$lpm1}'>$lpm1</a> </li>";
                $pagination .= "<li> <a class='go-page' data-page='{$lastpage}'>$lastpage</a> </li>";
            } else {
                $pagination .= "<li> <a class='go-page' data-page='1'>1</a> </li>";
                $pagination .= "<li> <a class='go-page' data-page='2'>2</a> </li>";
                $pagination .= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li> <a class='current'>$counter</a> </li>";
                    else {
                        $pagination .= "<li> <a class='go-page' data-page='{$counter}'>$counter</a> </li>";
                    }
                }
            }
        }

        if ($page < $counter - 1) {
            $pagination .= "<li> <a class='go-page' data-page='{$next}'>Next</a> </li>";
            $pagination .= "<li> <a class='go-page' data-page='{$lastpage}'>Last</a> </li>";
        } else {
            $pagination .= "<li class='current'> <a class='current'>Next</a> </li>";
            $pagination .= "<li class='current'> <a class='current'>Last</a> </li>";
        }
        $pagination .= "</ul>\n";
    }

    return $pagination;
}

function result_search($array, $key, $value)
{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, result_search($subarray, $key, $value));
        }
    }

    return $results;
}