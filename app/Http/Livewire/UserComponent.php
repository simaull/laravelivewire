<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class UserComponent extends Component
{
    public $user_id, $users, $name, $email;
    public $updateMode = false;
//    protected $listeners = ['userAdded'];
    public function render()
    {
        $this->users = User::all();

        return view('admin.user.users');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
    }

    public function store()
    {
        $validate = $this->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);
        User::create($validate);
        session()->flash('message','Users Berhasil dibuat');
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $user = User::where('id',$id)->first();
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($this->user_id) {
            $user = User::find($this->user_id);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'User Berhasil di Update.');
            $this->resetInputFields();
        }
    }

    public function delete($id)
    {
        if($id){
            User::where('id',$id)->delete();
            session()->flash('message', 'User Berhasil di Hapus.');
        }
        else {
            session()->flash('message', 'User Belum Dipilih.');
        }
    }
}
