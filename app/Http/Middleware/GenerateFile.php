<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Storage;
use Closure;

use App\Model\Settings\AccessGroup;
use App\Model\Settings\ControllerDevice;
use App\Model\Settings\ControllerDeviceSub;
use App\Model\Settings\DoorGroup;
use App\Model\Settings\Holiday;
use App\Model\Settings\Schedule;
use App\Model\Settings\Reader;
use App\Model\Settings\Role;
use App\Model\Settings\MultimanRule;
use App\Model\Settings\IoLinker;

class GenerateFile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        //Generate file
        if($request->type == 'ag') {
          //AccessGroups
          $this->AccessGroups();
        } elseif ($request->type == 'controller') {
					//AccessGroups
					$this->AccessGroups();
					//CardSets
          $this->CardSets();
          //CommCfg
          $this->CommCfg();
          //EventMsg
          $this->EventMsg();
          //Elevator
          $this->Elevator();
					//Formats
          $this->Formats();
          //HereIAm
          $this->HereIAm();
          //InterfaceBoards
          $this->InterfaceBoards();
          //InterfaceTypes
          $this->InterfaceTypes();
          //InternalID
          $this->InternalID();
          //MsgPriority
          $this->MsgPriority();
					//PTPAdjacentReaders
					$this->PTPAdjacentReaders();
					//PTPGateways
					$this->PTPGateways();
          //KeyPadFile
          $this->KeyPadFile();
					//DoorGroups
					$this->DoorGroups();
					//IOLinkerRules
					$this->IOLinkerRules();
					//MultimanRules
					$this->MultimanRules();
					//PINReaders
					$this->PINReaders();
					//Readers
					$this->Readers();
					//Schedules
					$this->Schedules();
					//Holidays
					$this->Holidays();
        } elseif ($request->type == 'dg') {
          //DoorGroups
          $this->DoorGroups();
        } elseif ($request->type == 'iolinker') {
          //IOLinkerRules
          $this->IOLinkerRules();
        } elseif ($request->type == 'mm') {
          //MultimanRules
          $this->MultimanRules();
        } elseif ($request->type == 'pin') {
          //PINReaders
          $this->PINReaders();
        } elseif ($request->type == 'reader') {
          //Readers
          $this->Readers();
					//PTPAdjacentReaders
					$this->PTPAdjacentReaders();
        } elseif ($request->type == 'schedule') {
          //Schedules
          $this->Schedules();
        } elseif ($request->type == 'holiday') {
          //Holidays
          $this->Holidays();
        } elseif ($request->type == 'remedy') {
          //AG & Schedules for fetch remedy
          $this->Schedules();
          $this->AccessGroups();
        }
        return $response;
    }

    private function AccessGroups(){
      $accessgroups = AccessGroup::all();
      $accessgroup = "# Access Groups configuration file\n# accgrp d1 s1 d2 s2 d3 s3 d4 s4 d5 s5 d6 s6 d7 s7 d8 s8 d9 s9 ...\n";
      foreach ($accessgroups as $ag){
        $accessgroup .= $ag->id."  ".$ag->d1."  ".$ag->s1."  ".$ag->d2."  ".$ag->s2."  ".$ag->d3."  ".$ag->s3."  ".$ag->d4."  ".$ag->s4."  ".$ag->d5."  ".$ag->s5."  ".$ag->d6."  ".$ag->s6."  ".$ag->d7."  ".$ag->s7."  ".$ag->d8."  ".$ag->s8."  ".$ag->d9."  ".$ag->s9."  ".$ag->d10."  ".$ag->s10."  ".$ag->d11."  ".$ag->s11."  ".$ag->d12."  ".$ag->s12."  ".$ag->d13."  ".$ag->s13."  ".$ag->d14."  ".$ag->s14."  ".$ag->d15."  ".$ag->s15."  ".$ag->d16."  ".$ag->s16."  ".$ag->d17."  ".$ag->s17."  ".$ag->d18."  ".$ag->s18."  ".$ag->d19."  ".$ag->s19."  ".$ag->d20."  ".$ag->s20."  ".$ag->d21."  ".$ag->s21."  ".$ag->d22."  ".$ag->s22."  ".$ag->d23."  ".$ag->s23."  ".$ag->d24."  ".$ag->s24."  ".$ag->d25."  ".$ag->s25."  ".$ag->d26."  ".$ag->s26."  ".$ag->d27."  ".$ag->s27."  ".$ag->d28."  ".$ag->s28."  ".$ag->d29."  ".$ag->s29."  ".$ag->d30."  ".$ag->s30."  ".$ag->d31."  ".$ag->s31."  ".$ag->d32."  ".$ag->s32."  ".$ag->d33."  ".$ag->s33."  ".$ag->d34."  ".$ag->s34."  ".$ag->d35."  ".$ag->s35."  ".$ag->d36."  ".$ag->s36."  ".$ag->d37."  ".$ag->s37."  ".$ag->d38."  ".$ag->s38."  ".$ag->d39."  ".$ag->s39."  ".$ag->d40."  ".$ag->s40."\n";
      }
      Storage::disk('local')->put('config/AccessGroups', $accessgroup);
    }
		
		private function CardSets(){
      $cardsets = "f 1 1\nf 2 2\nf 3 3\nf 4 4\nf 5 5\nf 6 6\nf 7 7\nf 8 8\nf 9 9\nf 10 10\nf 11 11\nf 12 12\nf 13 13\nf 14 14\nf 15 15\nf 16 16\nf 17 17\nf 18 18\nf 19 19\nf 20 20\nf 21 21\nf 22 22\nf 23 23\nf 24 24\nf 25 25\nf 26 26\nf 27 27\nf 28 28\nf 29 29\nf 30 30\nf 31 31\nf 32 32\nf 33 33\nf 34 34\nf 35 35\nf 36 36\nf 37 37\nf 38 38\nf 39 39\nf 40 40\nf 41 41\nf 42 42\nf 43 43\nf 44 44\nf 45 45\nf 46 46\nf 47 47\nf 48 48\nf 49 49\nf 50 50\nf 51 51\nf 52 52\nf 53 53\nf 54 54\nf 55 55\nf 56 56\nf 57 57\nf 58 58\nf 59 59\nf 60 60\nf 61 61\nf 62 62\nf 63 63\nf 64 64\nf 65 65\nf 66 66\nf 67 67\nf 68 68\nf 69 69\nf 70 70\nf 71 71\nf 72 72\nf 73 73\nf 74 74\nf 75 75\nf 76 76\nf 77 77\nf 78 78\nf 79 79\nf 80 80\nf 81 81\nf 82 82\nf 83 83\nf 84 84\nf 85 85\nf 86 86\nf 87 87\nf 88 88\nf 89 89\nf 90 90\nf 91 91\nf 92 92\nf 93 93\nf 94 94\nf 95 95\nf 96 96\nf 97 97\nf 98 98\nf 99 99\nf 100 100\nf 101 101\nf 102 102\nf 103 103\nf 104 104\nf 105 105\nf 106 106\nf 107 107\nf 108 108\nf 109 109\nf 110 110\nf 111 111\nf 112 112\nf 113 113\nf 114 114\nf 115 115\nf 116 116\nf 117 117\nf 118 118\nf 119 119\nf 120 120\nf 121 121\nf 122 122\nf 123 123\nf 124 124\nf 125 125\nf 126 126\nf 127 127\nf 128 128\nf 129 129\nf 130 130\nf 131 131\nf 132 132\nf 133 133\nf 134 134\nf 135 135\nf 136 136\nf 137 137\nf 138 138\nf 139 139\nf 140 140\nf 141 141\nf 142 142\nf 143 143\nf 144 144\nf 145 145\nf 146 146\nf 147 147\nf 148 148\nf 149 149\nf 150 150\nf 151 151\nf 152 152\nf 153 153\nf 154 154\nf 155 155\nf 156 156\nf 157 157\nf 158 158\nf 159 159\nf 160 160\nf 161 161\nf 162 162\nf 163 163\nf 164 164\nf 165 165\nf 166 166\nf 167 167\nf 168 168\nf 169 169\nf 170 170\nf 171 171\nf 172 172\nf 173 173\nf 174 174\nf 175 175\nf 176 176\nf 177 177\nf 178 178\nf 179 179\nf 180 180\nf 181 181\nf 182 182\nf 183 183\nf 184 184\nf 185 185\nf 186 186\nf 187 187\nf 188 188\nf 189 189\nf 190 190\nf 191 191\nf 192 192\nf 193 193\nf 194 194\nf 195 195\nf 196 196\nf 197 197\nf 198 198\nf 199 199\nf 200 200\nf 201 201\nf 202 202\nf 203 203\nf 204 204\nf 205 205\nf 206 206\nf 207 207\nf 208 208\nf 209 209\nf 210 210\nf 211 211\nf 212 212\nf 213 213\nf 214 214\nf 215 215\nf 216 216\nf 217 217\nf 218 218\nf 219 219\nf 220 220\nf 221 221\nf 222 222\nf 223 223\nf 224 224\nf 225 225\nf 226 226\nf 227 227\nf 228 228\nf 229 229\nf 230 230\nf 231 231\nf 232 232\nf 233 233\nf 234 234\nf 235 235\nf 236 236\nf 237 237\nf 238 238\nf 239 239\nf 240 240\nf 241 241\nf 242 242\nf 243 243\nf 244 244\nf 245 245\nf 246 246\nf 247 247\nf 248 248\nf 249 249\nf 250 250\nf 251 251\nf 252 252\nf 253 253\n";
      Storage::disk('local')->put('config/CardSets', $cardsets	);
    }

    private function CommCfg(){
      $commcfg = "#\n# commtask configuration parameters\n# ------------------------------------------------------\n# Connect to host on startup (1=>yes)\nconnect_on_start 1 \n# If contacted by a host, maintain connection without callback (1=>yes)\nmaintain_host_connection 1\n# Call the host machine on this port\nconnection_port 4070\n# Listen on this port for host connection requests\nlisten_port 4050\n# If a connect attempt fails, wait this interval (~secs)\n# before attempting to connect to the next host in CommHosts\nreconnect_interval 3\n# If no messages are received from the host within this interval (~min) \n# then disconnect. (timeout = 0 => no timeout, continuous connection)\ntimeout 0 \n# Encrypt host communications (0=no, 1=yes)\nuse_encryption 0\n# If a response to a host request is not received within this time (~secs),\n# then disconnect.\nmessage_response_time 0\n";
      Storage::disk('local')->put('config/CommCfg', $commcfg);
    }

    private function DoorGroups(){
      $doorgroups = DoorGroup::all();
      $doorgroup = "# Door Groups configuration file\n# dgid NumReaders RdrID1 RdrID2 RdrID3\n";
      foreach ($doorgroups as $dg){
        switch ($dg->dgcount) {
          case 1:
            $doorgroup .= $dg->id."  ".$dg->dgcount."  ".$dg->r1."\n";
            break;
          case 2:
            $doorgroup .= $dg->id."  ".$dg->dgcount."  ".$dg->r1."  ".$dg->r2."\n";
            break;
          case 3:
            $doorgroup .= $dg->id."  ".$dg->dgcount."  ".$dg->r1."  ".$dg->r2."  ".$dg->r3."\n";
            break;
          case 4:
            $doorgroup .= $dg->id."  ".$dg->dgcount."  ".$dg->r1."  ".$dg->r2."  ".$dg->r3."  ".$dg->r4."\n";
            break;
          case 5:
            $doorgroup .= $dg->id."  ".$dg->dgcount."  ".$dg->r1."  ".$dg->r2."  ".$dg->r3."  ".$dg->r4."  ".$dg->r5."\n";
            break;
          case 6:
            $doorgroup .= $dg->id."  ".$dg->dgcount."  ".$dg->r1."  ".$dg->r2."  ".$dg->r3."  ".$dg->r4."  ".$dg->r5."  ".$dg->r6."\n";
            break;
          case 7:
            $doorgroup .= $dg->id."  ".$dg->dgcount."  ".$dg->r1."  ".$dg->r2."  ".$dg->r3."  ".$dg->r4."  ".$dg->r5."  ".$dg->r6."  ".$dg->r7."\n";
            break;
          case 8:
            $doorgroup .= $dg->id."  ".$dg->dgcount."  ".$dg->r1."  ".$dg->r2."  ".$dg->r3."  ".$dg->r4."  ".$dg->r5."  ".$dg->r6."  ".$dg->r7."  ".$dg->r8."\n";
            break;
        }
      }
      Storage::disk('local')->put('config/DoorGroups', $doorgroup);
    }

    private function Elevator(){

    }
		
		private function Formats(){
      $formats = "252 /mnt/apps/data/config/formats/h10303.vff\n253 /mnt/apps/data/config/formats/h10301_0.vff\n1 /mnt/apps/data/config/formats/h10301_1.vff\n2 /mnt/apps/data/config/formats/h10301_2.vff\n3 /mnt/apps/data/config/formats/h10301_3.vff\n4 /mnt/apps/data/config/formats/h10301_4.vff\n5 /mnt/apps/data/config/formats/h10301_5.vff\n6 /mnt/apps/data/config/formats/h10301_6.vff\n7 /mnt/apps/data/config/formats/h10301_7.vff\n8 /mnt/apps/data/config/formats/h10301_8.vff\n9 /mnt/apps/data/config/formats/h10301_9.vff\n10 /mnt/apps/data/config/formats/h10301_10.vff\n11 /mnt/apps/data/config/formats/h10301_11.vff\n12 /mnt/apps/data/config/formats/h10301_12.vff\n13 /mnt/apps/data/config/formats/h10301_13.vff\n14 /mnt/apps/data/config/formats/h10301_14.vff\n15 /mnt/apps/data/config/formats/h10301_15.vff\n16 /mnt/apps/data/config/formats/h10301_16.vff\n17 /mnt/apps/data/config/formats/h10301_17.vff\n18 /mnt/apps/data/config/formats/h10301_18.vff\n19 /mnt/apps/data/config/formats/h10301_19.vff\n20 /mnt/apps/data/config/formats/h10301_20.vff\n21 /mnt/apps/data/config/formats/h10301_21.vff\n22 /mnt/apps/data/config/formats/h10301_22.vff\n23 /mnt/apps/data/config/formats/h10301_23.vff\n24 /mnt/apps/data/config/formats/h10301_24.vff\n25 /mnt/apps/data/config/formats/h10301_25.vff\n26 /mnt/apps/data/config/formats/h10301_26.vff\n27 /mnt/apps/data/config/formats/h10301_27.vff\n28 /mnt/apps/data/config/formats/h10301_28.vff\n29 /mnt/apps/data/config/formats/h10301_29.vff\n30 /mnt/apps/data/config/formats/h10301_30.vff\n31 /mnt/apps/data/config/formats/h10301_31.vff\n32 /mnt/apps/data/config/formats/h10301_32.vff\n33 /mnt/apps/data/config/formats/h10301_33.vff\n34 /mnt/apps/data/config/formats/h10301_34.vff\n35 /mnt/apps/data/config/formats/h10301_35.vff\n36 /mnt/apps/data/config/formats/h10301_36.vff\n37 /mnt/apps/data/config/formats/h10301_37.vff\n38 /mnt/apps/data/config/formats/h10301_38.vff\n39 /mnt/apps/data/config/formats/h10301_39.vff\n40 /mnt/apps/data/config/formats/h10301_40.vff\n41 /mnt/apps/data/config/formats/h10301_41.vff\n42 /mnt/apps/data/config/formats/h10301_42.vff\n43 /mnt/apps/data/config/formats/h10301_43.vff\n44 /mnt/apps/data/config/formats/h10301_44.vff\n45 /mnt/apps/data/config/formats/h10301_45.vff\n46 /mnt/apps/data/config/formats/h10301_46.vff\n47 /mnt/apps/data/config/formats/h10301_47.vff\n48 /mnt/apps/data/config/formats/h10301_48.vff\n49 /mnt/apps/data/config/formats/h10301_49.vff\n50 /mnt/apps/data/config/formats/h10301_50.vff\n51 /mnt/apps/data/config/formats/h10301_51.vff\n52 /mnt/apps/data/config/formats/h10301_52.vff\n53 /mnt/apps/data/config/formats/h10301_53.vff\n54 /mnt/apps/data/config/formats/h10301_54.vff\n55 /mnt/apps/data/config/formats/h10301_55.vff\n56 /mnt/apps/data/config/formats/h10301_56.vff\n57 /mnt/apps/data/config/formats/h10301_57.vff\n58 /mnt/apps/data/config/formats/h10301_58.vff\n59 /mnt/apps/data/config/formats/h10301_59.vff\n60 /mnt/apps/data/config/formats/h10301_60.vff\n61 /mnt/apps/data/config/formats/h10301_61.vff\n62 /mnt/apps/data/config/formats/h10301_62.vff\n63 /mnt/apps/data/config/formats/h10301_63.vff\n64 /mnt/apps/data/config/formats/h10301_64.vff\n65 /mnt/apps/data/config/formats/h10301_65.vff\n66 /mnt/apps/data/config/formats/h10301_66.vff\n67 /mnt/apps/data/config/formats/h10301_67.vff\n68 /mnt/apps/data/config/formats/h10301_68.vff\n69 /mnt/apps/data/config/formats/h10301_69.vff\n70 /mnt/apps/data/config/formats/h10301_70.vff\n71 /mnt/apps/data/config/formats/h10301_71.vff\n72 /mnt/apps/data/config/formats/h10301_72.vff\n73 /mnt/apps/data/config/formats/h10301_73.vff\n74 /mnt/apps/data/config/formats/h10301_74.vff\n75 /mnt/apps/data/config/formats/h10301_75.vff\n76 /mnt/apps/data/config/formats/h10301_76.vff\n77 /mnt/apps/data/config/formats/h10301_77.vff\n78 /mnt/apps/data/config/formats/h10301_78.vff\n79 /mnt/apps/data/config/formats/h10301_79.vff\n80 /mnt/apps/data/config/formats/h10301_80.vff\n81 /mnt/apps/data/config/formats/h10301_81.vff\n82 /mnt/apps/data/config/formats/h10301_82.vff\n83 /mnt/apps/data/config/formats/h10301_83.vff\n84 /mnt/apps/data/config/formats/h10301_84.vff\n85 /mnt/apps/data/config/formats/h10301_85.vff\n86 /mnt/apps/data/config/formats/h10301_86.vff\n87 /mnt/apps/data/config/formats/h10301_87.vff\n88 /mnt/apps/data/config/formats/h10301_88.vff\n89 /mnt/apps/data/config/formats/h10301_89.vff\n90 /mnt/apps/data/config/formats/h10301_90.vff\n91 /mnt/apps/data/config/formats/h10301_91.vff\n92 /mnt/apps/data/config/formats/h10301_92.vff\n93 /mnt/apps/data/config/formats/h10301_93.vff\n94 /mnt/apps/data/config/formats/h10301_94.vff\n95 /mnt/apps/data/config/formats/h10301_95.vff\n96 /mnt/apps/data/config/formats/h10301_96.vff\n97 /mnt/apps/data/config/formats/h10301_97.vff\n98 /mnt/apps/data/config/formats/h10301_98.vff\n99 /mnt/apps/data/config/formats/h10301_99.vff\n100 /mnt/apps/data/config/formats/h10301_100.vff\n101 /mnt/apps/data/config/formats/h10301_101.vff\n102 /mnt/apps/data/config/formats/h10301_102.vff\n103 /mnt/apps/data/config/formats/h10301_103.vff\n104 /mnt/apps/data/config/formats/h10301_104.vff\n105 /mnt/apps/data/config/formats/h10301_105.vff\n106 /mnt/apps/data/config/formats/h10301_106.vff\n107 /mnt/apps/data/config/formats/h10301_107.vff\n108 /mnt/apps/data/config/formats/h10301_108.vff\n109 /mnt/apps/data/config/formats/h10301_109.vff\n110 /mnt/apps/data/config/formats/h10301_110.vff\n111 /mnt/apps/data/config/formats/h10301_111.vff\n112 /mnt/apps/data/config/formats/h10301_112.vff\n113 /mnt/apps/data/config/formats/h10301_113.vff\n114 /mnt/apps/data/config/formats/h10301_114.vff\n115 /mnt/apps/data/config/formats/h10301_115.vff\n116 /mnt/apps/data/config/formats/h10301_116.vff\n117 /mnt/apps/data/config/formats/h10301_117.vff\n118 /mnt/apps/data/config/formats/h10301_118.vff\n119 /mnt/apps/data/config/formats/h10301_119.vff\n120 /mnt/apps/data/config/formats/h10301_120.vff\n121 /mnt/apps/data/config/formats/h10301_121.vff\n122 /mnt/apps/data/config/formats/h10301_122.vff\n123 /mnt/apps/data/config/formats/h10301_123.vff\n124 /mnt/apps/data/config/formats/h10301_124.vff\n125 /mnt/apps/data/config/formats/h10301_125.vff\n126 /mnt/apps/data/config/formats/h10301_126.vff\n127 /mnt/apps/data/config/formats/h10301_127.vff\n128 /mnt/apps/data/config/formats/h10301_128.vff\n129 /mnt/apps/data/config/formats/h10301_129.vff\n130 /mnt/apps/data/config/formats/h10301_130.vff\n131 /mnt/apps/data/config/formats/h10301_131.vff\n132 /mnt/apps/data/config/formats/h10301_132.vff\n133 /mnt/apps/data/config/formats/h10301_133.vff\n134 /mnt/apps/data/config/formats/h10301_134.vff\n135 /mnt/apps/data/config/formats/h10301_135.vff\n136 /mnt/apps/data/config/formats/h10301_136.vff\n137 /mnt/apps/data/config/formats/h10301_137.vff\n138 /mnt/apps/data/config/formats/h10301_138.vff\n139 /mnt/apps/data/config/formats/h10301_139.vff\n140 /mnt/apps/data/config/formats/h10301_140.vff\n141 /mnt/apps/data/config/formats/h10301_141.vff\n142 /mnt/apps/data/config/formats/h10301_142.vff\n143 /mnt/apps/data/config/formats/h10301_143.vff\n144 /mnt/apps/data/config/formats/h10301_144.vff\n145 /mnt/apps/data/config/formats/h10301_145.vff\n146 /mnt/apps/data/config/formats/h10301_146.vff\n147 /mnt/apps/data/config/formats/h10301_147.vff\n148 /mnt/apps/data/config/formats/h10301_148.vff\n149 /mnt/apps/data/config/formats/h10301_149.vff\n150 /mnt/apps/data/config/formats/h10301_150.vff\n151 /mnt/apps/data/config/formats/h10301_151.vff\n152 /mnt/apps/data/config/formats/h10301_152.vff\n153 /mnt/apps/data/config/formats/h10301_153.vff\n154 /mnt/apps/data/config/formats/h10301_154.vff\n155 /mnt/apps/data/config/formats/h10301_155.vff\n156 /mnt/apps/data/config/formats/h10301_156.vff\n157 /mnt/apps/data/config/formats/h10301_157.vff\n158 /mnt/apps/data/config/formats/h10301_158.vff\n159 /mnt/apps/data/config/formats/h10301_159.vff\n160 /mnt/apps/data/config/formats/h10301_160.vff\n161 /mnt/apps/data/config/formats/h10301_161.vff\n162 /mnt/apps/data/config/formats/h10301_162.vff\n163 /mnt/apps/data/config/formats/h10301_163.vff\n164 /mnt/apps/data/config/formats/h10301_164.vff\n165 /mnt/apps/data/config/formats/h10301_165.vff\n166 /mnt/apps/data/config/formats/h10301_166.vff\n167 /mnt/apps/data/config/formats/h10301_167.vff\n168 /mnt/apps/data/config/formats/h10301_168.vff\n169 /mnt/apps/data/config/formats/h10301_169.vff\n170 /mnt/apps/data/config/formats/h10301_170.vff\n171 /mnt/apps/data/config/formats/h10301_171.vff\n172 /mnt/apps/data/config/formats/h10301_172.vff\n173 /mnt/apps/data/config/formats/h10301_173.vff\n174 /mnt/apps/data/config/formats/h10301_174.vff\n175 /mnt/apps/data/config/formats/h10301_175.vff\n176 /mnt/apps/data/config/formats/h10301_176.vff\n177 /mnt/apps/data/config/formats/h10301_177.vff\n178 /mnt/apps/data/config/formats/h10301_178.vff\n179 /mnt/apps/data/config/formats/h10301_179.vff\n180 /mnt/apps/data/config/formats/h10301_180.vff\n181 /mnt/apps/data/config/formats/h10301_181.vff\n182 /mnt/apps/data/config/formats/h10301_182.vff\n183 /mnt/apps/data/config/formats/h10301_183.vff\n184 /mnt/apps/data/config/formats/h10301_184.vff\n185 /mnt/apps/data/config/formats/h10301_185.vff\n186 /mnt/apps/data/config/formats/h10301_186.vff\n187 /mnt/apps/data/config/formats/h10301_187.vff\n188 /mnt/apps/data/config/formats/h10301_188.vff\n189 /mnt/apps/data/config/formats/h10301_189.vff\n190 /mnt/apps/data/config/formats/h10301_190.vff\n191 /mnt/apps/data/config/formats/h10301_191.vff\n192 /mnt/apps/data/config/formats/h10301_192.vff\n193 /mnt/apps/data/config/formats/h10301_193.vff\n194 /mnt/apps/data/config/formats/h10301_194.vff\n195 /mnt/apps/data/config/formats/h10301_195.vff\n196 /mnt/apps/data/config/formats/h10301_196.vff\n197 /mnt/apps/data/config/formats/h10301_197.vff\n198 /mnt/apps/data/config/formats/h10301_198.vff\n199 /mnt/apps/data/config/formats/h10301_199.vff\n200 /mnt/apps/data/config/formats/h10301_200.vff\n201 /mnt/apps/data/config/formats/h10301_201.vff\n202 /mnt/apps/data/config/formats/h10301_202.vff\n203 /mnt/apps/data/config/formats/h10301_203.vff\n204 /mnt/apps/data/config/formats/h10301_204.vff\n205 /mnt/apps/data/config/formats/h10301_205.vff\n206 /mnt/apps/data/config/formats/h10301_206.vff\n207 /mnt/apps/data/config/formats/h10301_207.vff\n208 /mnt/apps/data/config/formats/h10301_208.vff\n209 /mnt/apps/data/config/formats/h10301_209.vff\n210 /mnt/apps/data/config/formats/h10301_210.vff\n211 /mnt/apps/data/config/formats/h10301_211.vff\n212 /mnt/apps/data/config/formats/h10301_212.vff\n213 /mnt/apps/data/config/formats/h10301_213.vff\n214 /mnt/apps/data/config/formats/h10301_214.vff\n215 /mnt/apps/data/config/formats/h10301_215.vff\n216 /mnt/apps/data/config/formats/h10301_216.vff\n217 /mnt/apps/data/config/formats/h10301_217.vff\n218 /mnt/apps/data/config/formats/h10301_218.vff\n219 /mnt/apps/data/config/formats/h10301_219.vff\n220 /mnt/apps/data/config/formats/h10301_220.vff\n221 /mnt/apps/data/config/formats/h10301_221.vff\n222 /mnt/apps/data/config/formats/h10301_222.vff\n223 /mnt/apps/data/config/formats/h10301_223.vff\n224 /mnt/apps/data/config/formats/h10301_224.vff\n225 /mnt/apps/data/config/formats/h10301_225.vff\n226 /mnt/apps/data/config/formats/h10301_226.vff\n227 /mnt/apps/data/config/formats/h10301_227.vff\n228 /mnt/apps/data/config/formats/h10301_228.vff\n229 /mnt/apps/data/config/formats/h10301_229.vff\n230 /mnt/apps/data/config/formats/h10301_230.vff\n231 /mnt/apps/data/config/formats/h10301_231.vff\n232 /mnt/apps/data/config/formats/h10301_232.vff\n233 /mnt/apps/data/config/formats/h10301_233.vff\n234 /mnt/apps/data/config/formats/h10301_234.vff\n235 /mnt/apps/data/config/formats/h10301_235.vff\n236 /mnt/apps/data/config/formats/h10301_236.vff\n237 /mnt/apps/data/config/formats/h10301_237.vff\n238 /mnt/apps/data/config/formats/h10301_238.vff\n239 /mnt/apps/data/config/formats/h10301_239.vff\n240 /mnt/apps/data/config/formats/h10301_240.vff\n241 /mnt/apps/data/config/formats/h10301_241.vff\n242 /mnt/apps/data/config/formats/h10301_242.vff\n243 /mnt/apps/data/config/formats/h10301_243.vff\n244 /mnt/apps/data/config/formats/h10301_244.vff\n245 /mnt/apps/data/config/formats/h10301_245.vff\n246 /mnt/apps/data/config/formats/h10301_246.vff\n247 /mnt/apps/data/config/formats/h10301_247.vff\n248 /mnt/apps/data/config/formats/h10301_248.vff\n249 /mnt/apps/data/config/formats/h10301_249.vff\n250 /mnt/apps/data/config/formats/h10301_250.vff\n251 /mnt/apps/data/config/formats/36_1.vff\n";
      Storage::disk('local')->put('config/Formats', $formats	);
    }

    private function EventMsg(){
      $eventmsg = "# Event Msg configuration file\n# Associate each task event with a class code\n# DO NOT change TaskCodes or EventCodes\n# TaskCode(1-255) EventCode(1-255) ClassCode(1-255)\n# identtask start\n1 1 10\n# identtask deny access (card/PIN not found)\n1 22 20\n# identtask deny access (unknown reader)\n1 26 20\n# identtask deny access (card/PIN deleted)\n1 27 20\n# identtask card number found\n1 28 5\n# identtask host lookup\n1 40 5\n# identtask card updated\n1 50 5\n# identtask database changeover\n1 55 5\n# accesstask start\n2 1 10\n# accesstask grant access\n2 20 20\n# accesstask grant access (extended)\n2 21 20\n# accesstask deny access (no door access)\n2 23 20\n# accesstask deny access (door schedule)\n2 24 20\n# accesstask deny access (unknown reader)\n2 26 20\n# accesstask deny access (card/PIN deleted)\n2 27 20\n# accesstask deny access (wrong PIN)\n2 29 20\n# accesstask timed anti-passback violation\n2 30 20\n# accesstask real anti-passback violation\n2 31 20\n# accesstask area violation\n2 32 20\n# accesstask real anti-passback violation exit\n2 33 20\n# accesstask area violation exit\n2 34 20\n# accesstask deny access (door group/schedule not configured)\n2 35 20\n# accesstask deny access (not active)\n2 36 20\n# accesstask grant access (in-schedule elevator group)\n2 37 5\n# accesstask grant access (out-schedule elevator group)\n2 38 5\n# accesstask visitor message\n2 39 20\n# accesstask post access processor grant\n2 40 5\n# accesstask post access processor deny\n2 41 5\n# accesstask pin lockout\n2 42 5\n# accesstask reset card holder status\n2 45 5\n# accesstask card updated\n2 50 5\n# accesstask mm rule not met\n2 52 20\n# accesstask mm rule roleid invalid\n2 53 20\n# accesstask mm max supported rules already active\n2 54 20\n# accesstask mm rule sequence timeout\n2 55 20\n# accesstask mm rule unknown error occured\n2 56 20\n# accesstask mm rule roleids exceeded\n2 57 20\n# accesstask mm rule roleids out of sequence\n2 58 20\n# accesstask mm grant access\n2 59 20\n# accesstask mm extended grant access\n2 60 20\n# accesstask mm rule invalid for reader\n2 61 20\n# accesstask mm rule soft entry out of sequence\n2 62 20\n# accesstask mm soft entry grant access\n2 63 20\n# accesstask mm soft extended grant access\n2 64 20\n# accesstask mm soft rule not met\n2 65 20\n# accesstask mm rule seq timed-out while waiting for pin\n2 66 20 CHG\n# accesstask mm MultiMan Hard Rule Grant Access\n2 67 20\n# accesstask mm MultiMan Hard Rule Grant Access\n2 68 20\n# rs485 start\n3 1 10\n# rs485 interface function executed\n3 20 5\n# rs485 EEPROM values changed\n3 70 5\n# rs485 download interface program\n3 120 10\n# rs485 download interface EEPROM\n3 121 10\n# rs485 interface status\n3 122 10\n# Hi-O operation mode change.(EDGE EVO Only)\n3 123 10\n# io_linker start\n4 1 10\n# Interface event message (message not in EventMsg; class code in I/OLinkerRules)\n4 20 10\n# Local event message (message not in EventMsg; class code in I/OLinkerRules)\n4 21 10\n# eventlogger start\n5 1 10\n# eventlogger upload current messages\n5 20 5\n# eventlogger upload entire event log file\n5 21 5\n# eventlogger message id counter rollover\n5 23 5\n# eventlogger upload current messages by class code\n5 24 5\n# eventlogger upload all messages by class code\n5 25 5\n# eventlogger upload messages by message id\n5 26 5\n# eventlogger upload current messages by priority\n5 27 5\n# eventlogger upload all messages by priority\n5 28 5\n# localio start\n6 1 10\n# localio local function executed\n6 21 5\n# localio timer changed\n6 73 5\n# localio a/d limits changed\n6 93 5\n# localio debounce iterations changed\n6 94 5\n# localio poll delay changed\n6 95 5\n# monitor start\n7 1 10\n# monitor set time\n7 20 5\n# monitor set TZ\n7 21 5\n# monitor restart a task\n7 22 10\n# monitor stop a task\n7 23 10\n# Root Password Not Changed.\n7 24 5\n# sndrtask start\n8 1 10\n# rcvrtask start\n9 1 10\n# commtask start\n10 1 10\n# commtask invalid host called\n10 30 5\n# CGI task changed a configuration file\n12 24 5\n";
      Storage::disk('local')->put('config/EventMsg', $eventmsg);
    }

    private function HereIAm(){
      $hereiam = "# HereIAm configuration file\n# Here I Am time in seconds (range 20-86400, 0=disabled)\n60\n";
      Storage::disk('local')->put('config/HereIAm', $hereiam);
    }

    private function Holidays(){
      $holidays = Holiday::all();
      $holiday = "# Holidays configuration file\n# HolidayGroup Month Day (Year)\n#     -- missing year implies every year\n#groupid  month   day\n\n";
      foreach ($holidays as $h){
        $holiday .= "    ".$h->holid."       ".$h->month."      ".$h->day."      # ".$h->name."\n";
      }
      Storage::disk('local')->put('config/Holidays', $holiday);
    }

    private function InterfaceBoards(){
			$controllers = ControllerDevice::all();
			foreach($controllers as $controller){
				$subcontrollers = ControllerDeviceSub::where('iid', $controller->iid)->get();
				$ifaces = array("0", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
				$prints = array("   a  /mnt/apps/data/config/interfaces/V100EE_r111.hex  38400", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
				foreach($subcontrollers as $key => $subcontroller){
					if($subcontroller->device=='V100'){
						$print = "   a  /mnt/apps/data/config/interfaces/V100EE_r111.hex  38400";
					}else if($subcontroller->device=='V200'){
						$print = "   i  /mnt/apps/data/config/interfaces/V200EE_r105.hex  38400";
					}else if($subcontroller->device=='V300'){
						$print = "   o  /mnt/apps/data/config/interfaces/V300EE_r104.hex  38400";
					}else{
						$print = "";
					}
					if($controller->device=="V1000"){
						$ifaces[$key] = $subcontroller->iface;
						$prints[$key] = $print;
					}
				}
				
				$interfaceboards = "# Interface Boards configuration file\n# IF  Type  EEPromName                                  TargetBaudRate\n  $ifaces[0] $prints[0]\n  $ifaces[1] $prints[1]\n  $ifaces[2] $prints[2]\n  $ifaces[3] $prints[3]\n  $ifaces[4] $prints[4]\n  $ifaces[5] $prints[5]\n  $ifaces[6] $prints[6]\n  $ifaces[7] $prints[7]\n  $ifaces[8] $prints[8]\n  $ifaces[9] $prints[9]\n  $ifaces[10] $prints[10]\n  $ifaces[11] $prints[11]\n  $ifaces[12] $prints[12]\n  $ifaces[13] $prints[13]\n  $ifaces[14] $prints[14]\n  $ifaces[15] $prints[15]\n  $ifaces[16] $prints[16]\n  $ifaces[17] $prints[17]\n  $ifaces[18] $prints[18]\n  $ifaces[19] $prints[19]\n  $ifaces[20] $prints[20]\n  $ifaces[21] $prints[21]\n  $ifaces[22] $prints[22]\n  $ifaces[23] $prints[23]\n  $ifaces[24] $prints[24]\n  $ifaces[25] $prints[25]\n  $ifaces[26] $prints[26]\n  $ifaces[27] $prints[27]\n  $ifaces[28] $prints[28]\n  $ifaces[29] $prints[29]\n  $ifaces[30] $prints[30]\n  $ifaces[31] $prints[31]\n";
				Storage::disk('local')->put('config/InterfaceBoards/'.$controller->iid.'/InterfaceBoards', $interfaceboards);
			}
    }

    private function InterfaceTypes(){
      $interfacetypes = "# Interface Types configuration file\n# Type ProgramName                                LocationsFile\na  /mnt/apps/data/config/interfaces/V100_r114.hex /mnt/apps/data/config/interfaces/AccessLocs\ni  /mnt/apps/data/config/interfaces/V200_r106.hex /mnt/apps/data/config/interfaces/InputLocs\no  /mnt/apps/data/config/interfaces/V300_r107.hex /mnt/apps/data/config/interfaces/OutputLocs\na2 /mnt/flash/InterfaceConfig/V101_r200.hex /mnt/flash/InterfaceConfig/AccessLocs\na3 /mnt/flash/InterfaceConfig/V050_r200.hex /mnt/flash/InterfaceConfig/AccessLocs\na4 /mnt/flash/InterfaceConfig/V101_r200.hex /mnt/flash/InterfaceConfig/AccessLocs\na5 /mnt/flash/InterfaceConfig/V050_r200.hex /mnt/flash/InterfaceConfig/AccessLocs\n";
      Storage::disk('local')->put('config/InterfaceTypes', $interfacetypes);
    }
		
		private function InternalID(){
			$controllers = ControllerDevice::all();
			foreach($controllers as $controller){
				try {
					$contents = '';
					$contents = file_get_contents('ftp://root:pass@'.$controller->ip.'/mnt/apps/data/config/InternalID');
					
					$explodes = explode("\n", $contents);

					$internalid = "";
					foreach ($explodes as $key => $explode) {
						if(strpos($explode, "INTID=") !== false) {
							$internalid .= explode("=", $explode)[0]."=".$controller->iid."\n";
						}else
							$internalid .= $explode."\n";
					}
					$removeblank = implode("\n", array_filter(explode("\n", $internalid)));
					Storage::disk('local')->put('config/InternalID/'.$controller->iid.'/InternalID', $removeblank);
				} catch (\Exception $e) {
          $errors[] = ["Could not connect to ".$controller->name." IP: ".$controller->ip];
        }
			}
    }

    private function IOLinkerRules(){
			$io_linker = "# IO Linker Rules configuration file\n#               IO Linker Rules\n# ------------------------------------------------\n# Supported syntax: \n# set <option>\n# ... and ...\n# <output> = <expression>\n# where output is:\n#   O(<interface>,<function_code>)                 - output (set/clear type)\n#   OM(<interface>,<function_code>)                - momentary output\n#   E(<msgnum>,<interface>,<class_code>)           - events to eventlogger\n#   ER(<msgnum>,<interface>,<reader>,<class_code>) - reader events to eventlogger\n#   G(<group_id>)                                  - activate output group\n#   L(<logical_id>)                                - set a logical value\n#   T(<timer_number>,<interval>,<timer_type>)      - define a timer\n#\n# and expressions are combinations of:\n#   I(<interface>,<status_bit>)                    - reflects status bit value\n#   I(<internal_id>,<interface>,<status_bit>)      - reflects status bit value\n#   L(<logical_id>)                                - logical bit value\n#   S(<schedule_number>)                           - true if in schedule\n#   T(<timer_number>)                              - reflects value of timer\n#   E(<taskcode>,<eventcode>)			   - event\nset peer_notification_interval 300\nset schedule_poll_interval 15\n#\n# Send event message 901 for Door 1 Forced Door Alarm on IF 0\n#E(901,00,20)=I(00,25)\n# Send event message 902 for Door 2 Forced Door Alarm on IF 0\n#E(902,00,20)=I(00,27)\n# Send event message 903 for Door 1 Door Held Alarm on IF 0\n#E(903,00,20)=I(00,24)\n# Send event message 904 for Door 2 Door Held Alarm on IF 0\n#E(904,00,20)=I(00,26)\n# Send event message 910 for Tamper Switch Alarm on IF 0\n#E(910,00,20)=I(00,19)\n# Send event message 911 for AC Failure Alarm on IF 0\n#E(911,00,20)=I(00,21)\n# Send event message 912 for Battery Failure Alarm on IF 0\n#E(912,00,20)=I(00,23)\n# Send event message 915 when any tamper input point changes state\n#    ... add additional entry for each interface on the system\n#E(915,32,20)=I(32,8) | I(00,18)\n#\n# Open both doors on IF 0 on a schedule if logical set\n#G(1)=S(1) & L(12)\n# Aux 1 Relay track Door 1 Door Forced Alarm on IF 0 if logical set\n#O(00,01)=I(00,25) & L(21)\n# Aux 2 Relay on IF 0 when Not in Schedule and logical set\n#O(00,17)=!S(1) & L(30)\n#\n# NOTE: The following rule is required for UL compliance.\n# Trigger local output if tamper changes state on the V1000\n# or any Vx00 attached to it.\n# e.g. O(32,00)=I(32,09) | I(00,19)  | I(01,19) .....\nO(32,00)=I(32,09)\n# --- DO NOT MODIFY CODE BELOW : AUTO-GENERATED CODE ---\n# ------ AUTO-GENERATED INPUT STATE ALARM REGION ---------------------\nT(1,2,DI)<=[interfaceid=0][reader=0]E(1,40)\nO(0,9)=[\]T(1)\nT(2,2,DI)<=[interfaceid=0][reader=1]E(1,40)\nO(0,25)=[\]T(2)\nSET O(0,2) FALSE\nSET O(0,18) FALSE\n# V100 | V2000 with Interface 0 Input Rules\n#E(1100,0,20)=I(0,19)\n#E(1000,0,20)=I(0,18)\n#E(1001,0,20)=I(0,20)\n#E(1002,0,20)=I(0,22)\n#E(1003,0,20)=I(0,12)\n#E(1004,0,20)=I(0,08)\n#E(1005,0,20)=I(0,14)\n#E(1006,0,20)=I(0,10)\n#E(1019,0,20)=I(0,25)\n#E(1020,0,20)=I(0,27)\n#E(1021,0,20)=I(0,24)\n#E(1022,0,20)=I(0,26)\n#E(1101,0,20)=I(0,21)\n#E(1102,0,20)=I(0,23)\n#E(1103,0,20)=I(0,13)\n#E(1104,0,20)=I(0,09)\n#E(1105,0,20)=I(0,15)\n#E(1106,0,20)=I(0,11)\n# ---- Door forced alarm silenced (only events) ----\n#O(0,11)=~I(0,25)\n#O(0,27)=~I(0,27)\n# ------------------------------------\n# ---- Door held alarm silenced (only events) ----\n#O(0,10)=~I(0,24)\n#O(0,26)=~I(0,26)\n# ------------------------------------\n# V1000 with Interface 32 Input Rules\n#E(1000,32,20)=I(32,08)\n#E(1001,32,20)=I(32,10)\n#E(1002,32,20)=I(32,12)\n#E(1003,32,20)=I(32,00)\n#E(1004,32,20)=I(32,02)\n#E(1100,32,20)=I(32,09)\n#E(1101,32,20)=I(32,11)\n#E(1102,32,20)=I(32,13)\n#E(1103,32,20)=I(32,01)\n#E(1104,32,20)=I(32,03)\n# --------------------------------------------------------------------\n# ------ AUTO-GENERATED DOOR AUTO-UNLOCK SCHEDULES REGION ------------\n# --------------------------------------------------------------------\n# ------ AUTO-GENERATED ELEVATOR AUTO-UNLOCK SCHEDULES REGION --------\nSET O(0,5) FALSE\nSET O(0,21) FALSE\n# --------------------------------------------------------------------\n# ------ AUTO-GENERATED ZONE REGION ----------------------------------\n# --------------------------------------------------------------------\n# ------ AUTO-GENERATED MAINTENANCE MODE REGION ----------------------\nSET L(5) FALSE\nSET L(6) FALSE\nSET L(7) FALSE\nSET L(8) FALSE\nSET L(9) FALSE\nSET L(10) FALSE\nSET L(2) FALSE\nSET L(11) FALSE\nSET L(4) FALSE\nSET L(12) FALSE\n# --------------------------------------------------------------------\n# ------ AUTO-GENERATED SAS REGION -----------------------------------\nSET O(0,160) FALSE\nSET O(0,162) FALSE\nSET O(0,161) FALSE\nSET O(0,163) FALSE\nSET O(32,12) FALSE\nSET O(32,13) FALSE\n# --------------------------------------------------------------------\n# ------ AUTO-GENERATED DOORS REGION ---------------------------------\nO(0,0)=L(2)|L(1)\nO(0,16)=L(4)|L(3)\n# --------------------------------------------------------------------\n# ------ AUTO-GENERATED READER LEDS REGION ---------------------------\n# --------------------------------------------------------------------\n# ------ AUTO-GENERATED LOGICAL VARIABLES REGION ---------------------\nSET L(1) FALSE\nSET L(3) FALSE\n# --------------------------------------------------------------------\n\n		      #V100 I/O Linker IF 00\n	#Force Open Alarm send event (9000) Door 1\nE(9000,00,30)=I(00,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,00,30)=I(00,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,00,30)=I(00,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,00,30)=I(00,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,00,30)=I(00,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,00,30)=I(00,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,00,30)=I(00,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,00,30)=I(00,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,00,30)=I(00,23)\n\n		      #V100 I/O Linker IF 01\n	#Force Open Alarm send event (9000) Door 1\nE(9000,01,30)=I(01,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,01,30)=I(01,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,01,30)=I(01,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,01,30)=I(01,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,01,30)=I(01,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,01,30)=I(01,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,01,30)=I(01,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,01,30)=I(01,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,01,30)=I(01,23)\n\n		      #V100 I/O Linker IF 02\n	#Force Open Alarm send event (9000) Door 1\nE(9000,02,30)=I(02,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,02,30)=I(02,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,02,30)=I(02,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,02,30)=I(02,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,02,30)=I(02,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,02,30)=I(02,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,02,30)=I(02,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,02,30)=I(02,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,02,30)=I(02,23)\n\n		      #V100 I/O Linker IF 03\n	#Force Open Alarm send event (9000) Door 1\nE(9000,03,30)=I(03,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,03,30)=I(03,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,03,30)=I(03,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,03,30)=I(03,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,03,30)=I(03,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,03,30)=I(03,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,03,30)=I(03,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,03,30)=I(03,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,03,30)=I(03,23)\n\n		      #V100 I/O Linker IF 04\n	#Force Open Alarm send event (9000) Door 1\nE(9000,04,30)=I(04,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,04,30)=I(04,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,04,30)=I(04,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,04,30)=I(04,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,04,30)=I(04,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,04,30)=I(04,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,04,30)=I(04,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,04,30)=I(04,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,04,30)=I(04,23)\n\n		      #V100 I/O Linker IF 05\n	#Force Open Alarm send event (9000) Door 1\nE(9000,05,30)=I(05,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,05,30)=I(05,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,05,30)=I(05,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,05,30)=I(05,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,05,30)=I(05,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,05,30)=I(05,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,05,30)=I(05,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,05,30)=I(05,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,05,30)=I(05,23)\n\n		      #V100 I/O Linker IF 06\n	#Force Open Alarm send event (9000) Door 1\nE(9000,06,30)=I(06,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,06,30)=I(06,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,06,30)=I(06,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,06,30)=I(06,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,06,30)=I(06,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,06,30)=I(06,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,06,30)=I(06,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,06,30)=I(06,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,06,30)=I(06,23)\n\n		      #V100 I/O Linker IF 07\n	#Force Open Alarm send event (9000) Door 1\nE(9000,07,30)=I(07,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,07,30)=I(07,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,07,30)=I(07,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,07,30)=I(07,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,07,30)=I(07,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,07,30)=I(07,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,07,30)=I(07,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,07,30)=I(07,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,07,30)=I(07,23)\n\n		      #V100 I/O Linker IF 08\n	#Force Open Alarm send event (9000) Door 1\nE(9000,08,30)=I(08,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,08,30)=I(08,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,08,30)=I(08,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,08,30)=I(08,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,08,30)=I(08,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,08,30)=I(08,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,08,30)=I(08,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,08,30)=I(08,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,08,30)=I(08,23)\n\n		      #V100 I/O Linker IF 09\n	#Force Open Alarm send event (9000) Door 1\nE(9000,09,30)=I(09,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,09,30)=I(09,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,09,30)=I(09,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,09,30)=I(09,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,09,30)=I(09,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,09,30)=I(09,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,09,30)=I(09,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,09,30)=I(09,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,09,30)=I(09,23)\n\n		      #V100 I/O Linker IF 10\n	#Force Open Alarm send event (9000) Door 1\nE(9000,10,30)=I(10,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,10,30)=I(10,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,10,30)=I(10,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,10,30)=I(10,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,10,30)=I(10,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,10,30)=I(10,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,10,30)=I(10,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,10,30)=I(10,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,10,30)=I(10,23)\n\n		      #V100 I/O Linker IF 11\n	#Force Open Alarm send event (9000) Door 1\nE(9000,11,30)=I(11,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,11,30)=I(11,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,11,30)=I(11,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,11,30)=I(11,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,11,30)=I(11,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,11,30)=I(11,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,11,30)=I(11,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,11,30)=I(11,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,11,30)=I(11,23)\n\n		      #V100 I/O Linker IF 12\n	#Force Open Alarm send event (9000) Door 1\nE(9000,12,30)=I(12,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,12,30)=I(12,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,12,30)=I(12,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,12,30)=I(12,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,12,30)=I(12,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,12,30)=I(12,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,12,30)=I(12,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,12,30)=I(12,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,12,30)=I(12,23)\n\n		      #V100 I/O Linker IF 13\n	#Force Open Alarm send event (9000) Door 1\nE(9000,13,30)=I(13,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,13,30)=I(13,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,13,30)=I(13,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,13,30)=I(13,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,13,30)=I(13,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,13,30)=I(13,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,13,30)=I(13,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,13,30)=I(13,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,13,30)=I(13,23)\n\n		      #V100 I/O Linker IF 14\n	#Force Open Alarm send event (9000) Door 1\nE(9000,14,30)=I(14,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,14,30)=I(14,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,14,30)=I(14,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,14,30)=I(14,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,14,30)=I(14,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,14,30)=I(14,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,14,30)=I(14,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,14,30)=I(14,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,14,30)=I(14,23)\n\n		      #V100 I/O Linker IF 15\n	#Force Open Alarm send event (9000) Door 1\nE(9000,15,30)=I(15,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,15,30)=I(15,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,15,30)=I(15,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,15,30)=I(15,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,15,30)=I(15,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,15,30)=I(15,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,15,30)=I(15,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,15,30)=I(15,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,15,30)=I(15,23)\n\n		      #V100 I/O Linker IF 16\n	#Force Open Alarm send event (9000) Door 1\nE(9000,16,30)=I(16,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,16,30)=I(16,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,16,30)=I(16,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,16,30)=I(16,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,16,30)=I(16,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,16,30)=I(16,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,16,30)=I(16,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,16,30)=I(16,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,16,30)=I(16,23)\n\n		      #V100 I/O Linker IF 17\n	#Force Open Alarm send event (9000) Door 1\nE(9000,17,30)=I(17,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,17,30)=I(17,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,17,30)=I(17,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,17,30)=I(17,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,17,30)=I(17,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,17,30)=I(17,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,17,30)=I(17,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,17,30)=I(17,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,17,30)=I(17,23)\n\n		      #V100 I/O Linker IF 18\n	#Force Open Alarm send event (9000) Door 1\nE(9000,18,30)=I(18,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,18,30)=I(18,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,18,30)=I(18,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,18,30)=I(18,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,18,30)=I(18,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,18,30)=I(18,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,18,30)=I(18,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,18,30)=I(18,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,18,30)=I(18,23)\n\n		      #V100 I/O Linker IF 19\n	#Force Open Alarm send event (9000) Door 1\nE(9000,19,30)=I(19,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,19,30)=I(19,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,19,30)=I(19,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,19,30)=I(19,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,19,30)=I(19,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,19,30)=I(19,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,19,30)=I(19,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,19,30)=I(19,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,19,30)=I(19,23)\n\n		      #V100 I/O Linker IF 20\n	#Force Open Alarm send event (9000) Door 1\nE(9000,20,30)=I(20,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,20,30)=I(20,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,20,30)=I(20,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,20,30)=I(20,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,20,30)=I(20,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,20,30)=I(20,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,20,30)=I(20,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,20,30)=I(20,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,20,30)=I(20,23)\n\n		      #V100 I/O Linker IF 21\n	#Force Open Alarm send event (9000) Door 1\nE(9000,21,30)=I(21,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,21,30)=I(21,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,21,30)=I(21,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,21,30)=I(21,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,21,30)=I(21,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,21,30)=I(21,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,21,30)=I(21,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,21,30)=I(21,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,21,30)=I(21,23)\n\n		      #V100 I/O Linker IF 22\n	#Force Open Alarm send event (9000) Door 1\nE(9000,22,30)=I(22,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,22,30)=I(22,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,22,30)=I(22,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,22,30)=I(22,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,22,30)=I(22,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,22,30)=I(22,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,22,30)=I(22,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,22,30)=I(22,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,22,30)=I(22,23)\n\n		      #V100 I/O Linker IF 23\n	#Force Open Alarm send event (9000) Door 1\nE(9000,23,30)=I(23,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,23,30)=I(23,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,23,30)=I(23,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,23,30)=I(23,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,23,30)=I(23,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,23,30)=I(23,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,23,30)=I(23,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,23,30)=I(23,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,23,30)=I(23,23)\n\n		      #V100 I/O Linker IF 24\n	#Force Open Alarm send event (9000) Door 1\nE(9000,24,30)=I(24,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,24,30)=I(24,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,24,30)=I(24,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,24,30)=I(24,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,24,30)=I(24,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,24,30)=I(24,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,24,30)=I(24,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,24,30)=I(24,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,24,30)=I(24,23)\n\n		      #V100 I/O Linker IF 25\n	#Force Open Alarm send event (9000) Door 1\nE(9000,25,30)=I(25,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,25,30)=I(25,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,25,30)=I(25,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,25,30)=I(25,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,25,30)=I(25,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,25,30)=I(25,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,25,30)=I(25,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,25,30)=I(25,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,25,30)=I(25,23)\n\n		      #V100 I/O Linker IF 26\n	#Force Open Alarm send event (9000) Door 1\nE(9000,26,30)=I(26,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,26,30)=I(26,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,26,30)=I(26,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,26,30)=I(26,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,26,30)=I(26,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,26,30)=I(26,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,26,30)=I(26,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,26,30)=I(26,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,26,30)=I(26,23)\n\n		      #V100 I/O Linker IF 27\n	#Force Open Alarm send event (9000) Door 1\nE(9000,27,30)=I(27,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,27,30)=I(27,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,27,30)=I(27,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,27,30)=I(27,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,27,30)=I(27,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,27,30)=I(27,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,27,30)=I(27,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,27,30)=I(27,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,27,30)=I(27,23)\n\n		      #V100 I/O Linker IF 28\n	#Force Open Alarm send event (9000) Door 1\nE(9000,28,30)=I(28,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,28,30)=I(28,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,28,30)=I(28,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,28,30)=I(28,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,28,30)=I(28,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,28,30)=I(28,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,28,30)=I(28,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,28,30)=I(28,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,28,30)=I(28,23)\n\n		      #V100 I/O Linker IF 29\n	#Force Open Alarm send event (9000) Door 1\nE(9000,29,30)=I(29,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,29,30)=I(29,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,29,30)=I(29,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,29,30)=I(29,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,29,30)=I(29,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,29,30)=I(29,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,29,30)=I(29,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,29,30)=I(29,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,29,30)=I(29,23)\n\n		      #V100 I/O Linker IF 30\n	#Force Open Alarm send event (9000) Door 1\nE(9000,30,30)=I(30,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,30,30)=I(30,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,30,30)=I(30,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,30,30)=I(30,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,30,30)=I(30,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,30,30)=I(30,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,30,30)=I(30,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,30,30)=I(30,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,30,30)=I(30,23)\n\n		      #V100 I/O Linker IF 31\n	#Force Open Alarm send event (9000) Door 1\nE(9000,31,30)=I(31,25)\n	#Door Held Alarm send event (9100) Door 1\nE(9100,31,30)=I(31,24)\n	#Door Switch Status send event (9500) Door 1\nE(9500,31,30)=I(31,12)\n	#Force Open Alarm send event (9010) Door 2\nE(9010,31,30)=I(31,27)\n	#Door Held Alarm send event (9110) Door 2\nE(9110,31,30)=I(31,26)\n	#Door Switch Status send event (9510) Door 2\nE(9510,31,30)=I(31,14)\n	#Tamper Switch Alarm send event (9200) Controller 1\nE(9200,31,30)=I(31,19)\n	#AC Failure Alarm send event (9300) Controller 1\nE(9300,31,30)=I(31,21)\n	#Battery Failure Alarm send event (9400) Controller 1\nE(9400,31,30)=I(31,23)\n\n			#V1000 I/O Linker\n	#Tamper Switch Alarm send event (10201)\nE(8100,00,30)=I(00,9)\n	#AC Failure Alarm send event (10301)\nE(8200,00,30)=I(00,11)\n	#Battery Switch Alarm send event (104013)\nE(8300,00,30)=I(00,13)\n	#Input 1 Alarm (V1000)\nE(8800,00,30)=I(00,1)\n	#Input 2 Alarm (V1000)\n#E(8900,00,30)=I(00,3)\n\n#disabled reader when aux==1 for mantrap\n#O(00,5)=I(01,32,00)\n#O(00,21)=I(01,32,00)\n#O(00,5)=I(02,32,00)\n#O(00,21)=I(02,32,00)\n\n#allow exit eventhough card is inactive\n#OM(00,23) = E(02,36) & I(02,00,06)\n\n# --------------------------------------------------------------------\n#OM(0,4)=I(2,0,7)\n#OM(0,21)=I(2,0,7)\n\n#Timer ID 99 dalam 30 detik bisa terjadi=Jika ada yang tap di IID 1, IF 0, Reader Left.\n#T(99,30,DI)=I(2,0,7)\n#Data holdline =Dalam timer 30 detik ID 99 DAN ada event task 2 event 2 (grant access)\n#O(0,5)=T(99)\n# Output momentary interface 0 pintu kedua= Dalam timer 30 detik Id 99 DAN IID 1 interface 0 reader ke 2 Card Read\n#OM(0,16)=T(99)&I(1,0,6)&E(2,20)\n#\n#T(99,0,DI)=I(1,0,6)\n\n##### COMMAND CENTER V1000+V100 #####\n##### Reader 1 kiri, Reader 2 kanan #####\n#Matiin Door Force Alarm saat boot up\n#O(00,11) = ~I(00,25)\n#T(99,5,DI) = I(1,0,7)&E(2,20)\n#O(00,11) = T(99)\n#I(1,0,7)&E(2,20)\n# 2. Baca Controller Site V200 IID 2, jika ada Door 1 Alarm Enabled. SB 26 -> Start count timer\n# 3. Dalam Timer 15 Detik DAN ada tap reader 1 + grant access -> door 2 Alarm Enabled\n\nSET L(9100) TRUE\nSET L(9110) TRUE\n\n#New IO Linker Starts From Here\n\n";

			$controllers = ControllerDevice::all();
			foreach($controllers as $controller){
				$io_linker_new = "";
				$io_linkers = IoLinker::where('iid', 0)->get();
				foreach ($io_linkers as $ir){
					if($ir->status==0)
						$io_linker_new .= $ir->lhs."=".$ir->rhs."     #".$ir->name."\n";
					else if($ir->status==1)
						$io_linker_new .= "#".$ir->lhs."=".$ir->rhs."     #".$ir->name."\n";
				}
				Storage::disk('local')->put('config/IOLinkerRules/'.$controller->iid.'/IOLinkerRules', $io_linker.$io_linker_new);
				
				$io_linker_new2 = "";
				$io_linkers2 = IoLinker::where('iid', $controller->iid)->get();
				foreach ($io_linkers2 as $ir2){
					if($ir2->status==0)
						$io_linker_new2 .= $ir2->lhs."=".$ir2->rhs."     #".$ir2->name."\n";
					else if($ir2->status==1)
						$io_linker_new2 .= "#".$ir2->lhs."=".$ir2->rhs."     #".$ir2->name."\n";
					Storage::disk('local')->put('config/IOLinkerRules/'.$ir2->iid.'/IOLinkerRules', $io_linker.$io_linker_new.$io_linker_new2);
				}
			}
		}

    private function KeyPadFile(){
      $keypadfile = "# Key Pad File config file\n# row definitions:\n# k key pad id number \n# r raw value and normalized value\n# e end of key pad definition\n#\n# r RawValue NormalizedValue\n# HID Keypad\nk 1\nr      0x0       0\nr      0x1       1\nr      0x2       2\nr      0x3       3\nr      0x4       4\nr      0x5       5\nr      0x6       6\nr      0x7       7\nr      0x8       8\nr      0x9       9\nr      0xa     0xa\nr      0xb     0xb\ne\n#Essex Key pad\nk 2\nr      0xf0      0\nr      0xe1      1\nr      0xd2      2\nr      0xc3      3\nr      0xb4      4\nr      0xa5      5\nr      0x96      6\nr      0x87      7\nr      0x78      8\nr      0x69      9\nr      0x5a    0xa\nr      0x4b    0xb\ne\n# OSDP Key Pad\nk 3\nr      0x00      0\nr      0x10      1\nr      0x20      2\nr      0x30      3\nr      0x40      4\nr      0x50      5\nr      0x60      6\nr      0x70      7\nr      0x80      8\nr      0x90      9\ne\n";
      Storage::disk('local')->put('config/KeyPadFile', $keypadfile);
    }

    private function MsgPriority(){
      $msgpriority = "#1648829177\n# Msg Priority configuration file\n# Associate priority with user defined class codes\n# ClassCode(1-255) Priority(0-255)\n# bit bucket\n      5                0\n# event \n     10               100 \n# alarm\n     20               200 \n# door\n     30 	      200\n";
      Storage::disk('local')->put('config/MsgPriority', $msgpriority);
    }

    private function MultimanRules(){
      $multiman_rules = MultimanRule::all();
      $multiman_rule = "# MultiMan Rules file\n# MultiManIdx PreserveSeq SeqTimeout EntryType RolesCnt RoleId1 RoleId2 RoleId3\n";
      foreach ($multiman_rules as $mr){
        switch ($mr->rolecount) {
          case 1:
            $multiman_rule .= $mr->id."  ".$mr->preserveseq."  ".$mr->tmout."  ".$mr->type."  ".$mr->rolecount."  ".$mr->role1."\n";
            break;
          case 2:
            $multiman_rule .= $mr->id."  ".$mr->preserveseq."  ".$mr->tmout."  ".$mr->type."  ".$mr->rolecount."  ".$mr->role1."  ".$mr->role2."\n";
            break;
          case 3:
            $multiman_rule .= $mr->id."  ".$mr->preserveseq."  ".$mr->tmout."  ".$mr->type."  ".$mr->rolecount."  ".$mr->role1."  ".$mr->role2."  ".$mr->role3."\n";
            break;
          case 4:
            $multiman_rule .= $mr->id."  ".$mr->preserveseq."  ".$mr->tmout."  ".$mr->type."  ".$mr->rolecount."  ".$mr->role1."  ".$mr->role2."  ".$mr->role3."  ".$mr->role4."\n";
            break;
          case 5:
            $multiman_rule .= $mr->id."  ".$mr->preserveseq."  ".$mr->tmout."  ".$mr->type."  ".$mr->rolecount."  ".$mr->role1."  ".$mr->role2."  ".$mr->role3."  ".$mr->role4."  ".$mr->role5."\n";
            break;
          case 6:
            $multiman_rule .= $mr->id."  ".$mr->preserveseq."  ".$mr->tmout."  ".$mr->type."  ".$mr->rolecount."  ".$mr->role1."  ".$mr->role2."  ".$mr->role3."  ".$mr->role4."  ".$mr->role5."  ".$mr->role6."\n";
            break;
          case 7:
            $multiman_rule .= $mr->id."  ".$mr->preserveseq."  ".$mr->tmout."  ".$mr->type."  ".$mr->rolecount."  ".$mr->role1."  ".$mr->role2."  ".$mr->role3."  ".$mr->role4."  ".$mr->role5."  ".$mr->role6."  ".$mr->role7."\n";
            break;
          case 8:
            $multiman_rule .= $mr->id."  ".$mr->preserveseq."  ".$mr->tmout."  ".$mr->type."  ".$mr->rolecount."  ".$mr->role1."  ".$mr->role2."  ".$mr->role3."  ".$mr->role4."  ".$mr->role5."  ".$mr->role6."  ".$mr->role7."  ".$mr->role8."\n";
            break;
        }
      }
      Storage::disk('local')->put('config/MultiManRules', $multiman_rule);
    }

    private function PINReaders(){

    }

    private function PTPAdjacentReaders(){
			$controllers = ControllerDevice::all();
      $readers = Reader::all();
			if(env('APP_COMPANY')=='BCA'){
				
			}else{
				foreach($controllers as $c){
					$adjacent = '';
					$adjacent = "# PTP Adjacent Readers configuration file\n# lists the internal ID of Gateway boards that are adjacent to a reader\n# ReaderID IID IID1 IID2 IID3\n";
					foreach($readers as $r){
						$entryexit = Reader::where('id', $r->id)->first();
						$iidperreaderid = Reader::select('iid')->where('entryid', $entryexit->entryid)->orWhere('exitid', $entryexit->exitid)->groupBy('iid')->get()->pluck('iid')->toArray();
						if(in_array($c->iid, $iidperreaderid)){
							$removeselfiid = array_diff($iidperreaderid, array($r->iid));
							$iid = implode('  ', $removeselfiid);
							$adjacent .= $r->id."  ".$iid."\n";
						}
					}
					Storage::disk('local')->put('config/PTPAdjacentReaders/'.$c->iid.'/PTPAdjacentReaders', $adjacent);
				}
			}
    }

    private function PTPGateways(){
      $controllers = ControllerDevice::all();
      $controller = "# PTP Gateways configuration file\n# maps each gateway IP to its internal ID\n#    GatewayIP    IID \n# XXX.XXX.XXX.XXX  1 \n# XXX.XXX.XXX.XXX  2\n";
			foreach ($controllers as $cont){
				$controller .= $cont->ip."  ".$cont->iid."\n";
			}
      Storage::disk('local')->put('config/PTPGateways', $controller);
    }

    private function Readers(){
      $readers = Reader::all();
      $reader = "# Readers configuration file\n#r IID IF a AM psup pincmd rdrtyp elev apbtyp tmout apbact entryid exitid lkup\n";
      foreach ($readers as $r){
        $reader .= $r->id."  ".$r->iid."  ".$r->iface."  ".$r->a."  ".$r->am."  ".$r->psup."  ".$r->pincmd."  ".$r->rdrtype."  ".$r->elevator."  ".$r->apbtype."  ".$r->tmout."  ".$r->apbact."  ".$r->exitid."  ".$r->entryid."  ".$r->lkup."  ".$r->multiman."\n";
      }
      Storage::disk('local')->put('config/Readers', $reader);
    }

    private function Schedules(){
      $schedules = Schedule::all();
      $schedule = "# Schedules configuration file\n# i=interval definition i S D I H1 M1 S1 H2 M2 S2\n# h=holiday interval definition h S D I H1 M1 S1 H2 M2 S2\n# S=schedule id\n# D=day code:normal (0=Sun, 1=Mon, 2=Tue, 3=Wed, 4=Thurs, 5=Fri, 6=Sat)\n# D=day code:holiday (1=Holiday Group 1, 2=Holiday Group 2, ....)\n# I=interval (0 to 5)\n# H1 M1 S1 = Start Hour Min Sec (0 0 0 to 23 59 59)\n# H2 M2 S2 = Stop Hour Min Sec (0 0 0 to 23 59 59)\n#\n# i S D I H1 M1 S1 H2 M2 S2\n# (7am-12pm & 12:50-6:30pm Mon-Sat)\n# (Holiday Group 1,2,4 - No Access on 1, 8am -12pm & 1pm-5pm on 2 & 4)\n";
      foreach ($schedules as $s){
        $schedule .= $s->definition."  ".$s->schedid."  ".$s->day."  ".$s->interval."  ".$s->h1."  ".$s->m1."  ".$s->s1."  ".$s->h2."  ".$s->m2."  ".$s->s2."\n";
      }
      Storage::disk('local')->put('config/Schedules', $schedule);
    }
}
