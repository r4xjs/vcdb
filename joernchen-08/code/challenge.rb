# Try to become "admin" on http://gettheadmin.herokuapp.com/
# Vector borrowed from real-world code ;)


# config/routes.rb:

  root 'login#login'
  post 'login' => 'login#login'
  get 'reset/:token' => 'login#password_reset'

# app/controllers/login_controller.rb

class LoginController < ApplicationController
  def login
    if request.post?
      user = User.where(login: params[:login]).first
      if !user.nil?
        if params[:password] == user.password
           render :text => "censored"
        end
        render :text => "Wrong Password"
        return
      end
    else
      render :template => "login/form"
    end
  end
  def password_reset
    @user = User.where(token: params[:token]).first
    if @user
      session[params[:token]] = @user.id
    else
      user_id = session[params[:token]]
      @user = User.find(user_id) if user_id
    end
    if !@user
      render :text => "no way!"
      return
    elsif params[:password] && @user && params[:password].length > 6
      @user.password = params[:password]
        if @user.save
          render :text => "password changed ;)"
          return
        end
    end
    render :text => "error saving password!"
  end

end
