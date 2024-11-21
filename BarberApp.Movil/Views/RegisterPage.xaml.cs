using BarberApp.Movil.ViewModels;

namespace BarberApp.Movil.Views;

public partial class RegisterPage : ContentPage
{
	public RegisterPage()
	{
		InitializeComponent();
		BindingContext = new RegisterViewModel();
	}
}