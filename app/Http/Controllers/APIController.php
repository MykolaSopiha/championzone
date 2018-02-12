<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Card;
use App\User;
use App\Token;
use DataTables;
use Auth;
use DB;

class APIController extends Controller
{
    public function getUsers(Request $request)
    {
        return Datatables::of(User::query())->addColumn('edit', function ($user) {
            $name = ($user->first_name != "" || $user->last_name != "") ? $user->first_name . " " . $user->last_name : $user->name;
            $role = $user->status;

            return "<a href='" . route('home.users.edit', $user->id) . "'><i class='fa fa-pencil' aria-hidden='true'></i></a>&nbsp" .
                "<a class='delete-user' data-name='{$name}' data-role='{$role}' href='" . route('home.users.delete', $user->id) . "'><i class=\"fa fa-ban\" aria-hidden=\"true\"></i></a>";

        })->addColumn('balance', function ($user) {
            $accounts = DB::table('accounts')->where('user_id', $user->id)->get();
            $balance = 0;
            foreach ($accounts as $acc) {
                $balance += $acc->price * $acc->rate / 100;
            }
            return round($balance, 2);
        })->make(true);
    }

    public function getCards(Request $request)
    {

        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        // getting total number records without any search
        $totalData = DB::table('cards')->count();
        // when there is no search parameter then total number rows = total number filtered rows.

        $conditions = [];

        parse_str($request->data, $filter);

        foreach ($filter as $key => $value) {
            if ($value != "" && $key != 'user_id') $conditions[] = [$key, '=', $value];
        }

        if (Auth::user()->status == 'mediabuyer' && !Auth::user()->TeamLead()) {
            $conditions[] = ['cards.user_id', '=', Auth::user()->id];
        }

        $myTeam = [];
        $myTeam[] = Auth::user()->id;

        if (Auth::user()->TeamLead() && Auth::user()->status != "admin") {
            $users = DB::table('users')->where([
                ['team_id', Auth::user()->team_id],
                ['id', '<>', Auth::user()->id]
            ])->get();

            foreach ($users as $user) {
                $myTeam[] = $user->id;
            }

            $cards = DB::table('cards')->whereIn('cards.user_id', $myTeam);
        } else {
            $cards = DB::table('cards')->where($conditions);
        }


        return DataTables::queryBuilder($cards)->editColumn('code', function ($card) {
            $code = decrypt($card->code);
            if (Auth::user()->status == 'mediabuyer') {
                return substr($code, 0, 4) . " " . substr($code, 4, 4) . " " . substr($code, 8, 4) . " " . substr($code, 12, 4) . " (" . decrypt($card->cw2) . ")";
            } else {
                return "<a href='" . url('/home/cards') . "/" . $card->id . "'>" . substr($code, 0, 4) . " " . substr($code, 4, 4) . " " . substr($code, 8, 4) . " " . substr($code, 12, 4) . " (" . decrypt($card->cw2) . ")" . "</a>";
            }
        })->addColumn('check', function ($card) {
            return "<input type='checkbox' class='shift_select' name='card[" . $card->id . "]'>";
        }, 0)->editColumn('user_id', function ($card) {
            $user = DB::table('users')->where('id', $card->user_id)->first();
            if (empty($user)) {
                return "<a href='" . url('home/cards') . "/" . $card->id . "'>" . 'Назначить пользователя' . "</a>";
            } else {
                return "<a href='" . url('home/cards') . "/" . $card->id . "'>" . $user->name . "</a>";
            }
        })->editColumn('date', function ($card) {
            return $card->date = substr($card->date, 0, 4) . "-" . substr($card->date, -2);
        })->addColumn('actions', function ($card) {
            if (Auth::user()->status == 'mediabuyer') {

                // user toolbar
                $acts = "<a href='" . url('/home/cards/') . "/" . $card->id . "/edit?action=unchain' title='Отвязать'>";
                $acts .= '<i class="fa fa-chain-broken" aria-hidden="true"></i>';
                $acts .= '</a>';

                return $acts;

            } else {

                //admin & accountant toolbar
                $acts = "<a href='" . url('/home/cards/') . "/" . $card->id . "/edit'>";
                if ($card->status === 'active') {
                    $acts .= '<i class="switch fa fa-toggle-on fa-lg" title="Вкл/Выкл" aria-hidden="true"></i>';
                } else {
                    $acts .= '<i class="switch fa fa-toggle-off fa-lg" title="Вкл/Выкл" aria-hidden="true"></i>';
                }
                $acts .= '</a>';

                return $acts . "<a class='remove-btn' href=" . url('/home/cards/') . "/" . $card->id . "><i class='remove fa fa-times fa-lg' title='Удалить' aria-hidden='true'></i></a>";

            }

        })->make(true);
    }

    public function getTokens(Request $request)
    {
        parse_str($request->data, $filter);
        $conditions = [];

        foreach ($filter as $key => $value) {
            if ($value != "") $conditions[$key] = $value;
        }

        if ((Auth::user()->status !== 'admin') && (Auth::user()->status !== 'accountant') && !Auth::user()->TeamLead()) {
            $conditions[] = ['user_id', Auth::user()->id];
        }

        $myTeam[] = Auth::user()->id;
        if (Auth::user()->TeamLead() && Auth::user()->status != "admin") {
            $users = DB::table('users')->where([
                ['team_id', Auth::user()->team_id],
                ['id', '<>', Auth::user()->id]
            ])->get();

            foreach ($users as $user) {
                $myTeam[] = $user->id;
            }

            $tokens = DB::table('tokens')->where($conditions)->whereIn('tokens.user_id', $myTeam);
        } else {
            $tokens = DB::table('tokens')->where($conditions);
        }


        return DataTables::queryBuilder($tokens)->orderBy('id', 'desc')
            ->addColumn('user_name', function ($token) {
                $user = DB::table('users')->where('id', $token->user_id)->limit(1)->get();
                return $user[0]->name;
            })
            ->editColumn('card_code', function ($token) {
                $code = decrypt($token->card_code);
                $code = substr($code, 0, 4) . "&nbsp;" . substr($code, 4, 4) . "&nbsp;" . substr($code, 8, 4) . "&nbsp;" . substr($code, 12);

                if (Auth::user()->status == 'admin' || Auth::user()->status == 'accountant') {
                    $card = Card::select('wallet')->where('id', $token->card_id)->first();
                    if (trim($card['wallet']) != '') {
                        return "<p class='has_wallet' data-wallet-code='" . $card->wallet . "'>" . $code . "</p>";
                    }
                }
                return $code;
            })
            ->editColumn('value', function ($token) {
                return number_format(floatval($token->value / 100), 2, ".", "");
            })
            ->editColumn('rate', function ($token) {
                return number_format(floatval($token->rate), 5, ".", "");
            })
            ->editColumn('action', function ($token) {
                $actions_RU = [
                    'deposit' => 'Пополнить',
                    'withdraw' => 'Списать',
                    'transfer' => 'Перевести'
                ];
                $action = $actions_RU[$token->action];

                if ($token->action == 'transfer') {
                    $action = '<p class="transfer_dest" data-card-code="' . decrypt($token->card2_code) . '">' . $action . '</p>';
                    $action .= "";
                }

                return $action;
            })
            ->editColumn('status', function ($token) {
                if (Auth::user()->status !== 'accountant' && Auth::user()->status !== 'admin') {
                    return "<p class='token_status'>" . $token->status . "</p>";
                } else {
                    return '<div class="dropdown">
	                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><span class="token_status">' . $token->status . '</span><span class="caret"></span></button>
	                                <ul class="dropdown-menu">
	                                    <li>
	                                        <a href="' . url("/home/tokens/") . '/' . $token->id . '/edit?status=active">active</a>
	                                    </li>
	                                    <li>
	                                        <a href="' . url("/home/tokens/") . '/' . $token->id . '/edit?status=confirmed">confirmed</a>
	                                    </li>
	                                    <li>
	                                        <a href="' . url("/home/tokens/") . '/' . $token->id . '/edit?status=trash">trash</a>
	                                    </li>
	                                </ul>
	                            </div>';
                }
            })
            ->addColumn('tools', function ($token) {
                if ($token->status == 'active' || Auth::user()->status == 'admin' || Auth::user()->status == 'accountant') {
                    return '<td>
								<a href="' . url('home/tokens') . '/' . $token->id . '">
	                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
	                            </a><br>
	                            <a class="remove-btn" href="' . url('home/tokens') . '/' . $token->id . '">
	                                <i class="remove fa fa-times fa-lg" title="Удалить" aria-hidden="true"></i>
	                            </a>
							</td>';
                } else {
                    return '--';
                }
            })
            ->make(true);

    }

    public function checkTokens(Request $request)
    {
        $coditions = [];
        $coditions[] = ['status', 'active'];

        if ($request->user_status != "farmer" && isset($request->user_id)) {
            if ($request->user_status == 'mediabuyer') {
                $coditions[] = ['user_id', $request->user_id];
            }
            return DB::table('tokens')->where($coditions)->count();
        }
        return 0;
    }

    public function createLead(Request $request)
    {

        if (!isset($_POST['name']) || !isset($_POST['phone']))
            if (isset($_SERVER['HTTP_REFERER']))
                header("Location: " . $_SERVER['HTTP_REFERER']);
            else
                header("Location: /");

        $data = json_decode(array_keys(json_decode($_POST['result'], true))[0]);

        $lead = new Lead();
        $lead->fill([
            'offer_id' => $data->offer_id,
            'stream_id' => $data->stream_id,
            'user_id' => $data->user_id,
            'name' => $data->name,
            'phone' => $data->phone,
            'tz' => $data->tz,
            'address' => $data->address,
            'country' => $data->country,
            'utm_source' => $data->utm_source,
            'utm_medium' => $data->utm_medium,
            'utm_campaign' => $data->utm_campaign,
            'utm_term' => $data->utm_term,
            'utm_content' => $data->utm_content,
            'check_sum' => $data->check_sum
        ]);
        // $card->save();
        return 500;

    }
}
