using BarberApp.Movil.ViewModels;

namespace BarberApp.Movil.Views;

public partial class LoginPage : ContentPage
{
    readonly LoginViewModel vm;
    public LoginPage()
	{
		InitializeComponent();
		BindingContext = vm = new LoginViewModel();
	}
    protected override void OnAppearing()
    {
        base.OnAppearing();
        vm.Email = "";
        vm.Password = "";
    }
}