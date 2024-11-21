using BarberApp.Movil.ViewModels;

namespace BarberApp.Movil.Views;

public partial class AccountPage : ContentPage
{
	readonly AccountViewModel vm;
	public AccountPage()
	{
		InitializeComponent();
		BindingContext = vm = new AccountViewModel();
	}
}