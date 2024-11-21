using BarberApp.Movil.Models;
using BarberApp.Movil.Settings;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using System.Net.Http.Json;

namespace BarberApp.Movil.ViewModels;
public partial class RegisterViewModel : ObservableObject
{
    readonly HttpClient httpClient = new();

    [ObservableProperty]
    private string _name;

    [ObservableProperty]
    private string _email;

    [ObservableProperty]
    private string _password;

    [ObservableProperty]
    private string _telefono;

    [RelayCommand]
    public async Task RegisterUser()
    {
        var usuario = new Usuario
        {
            Nombres = Name,
            Email = Email,
            Password = Password,
            Telefono = Telefono,
        };
        var result = await httpClient.PostAsJsonAsync(Settings.Settings.UrlApi + "/usuarios.php", usuario);
        if (result.IsSuccessStatusCode)
        {
            await Application.Current.MainPage.DisplayAlert("Aviso", "Usuario registrado correctamente", "Ok");
            await Shell.Current.Navigation.PopAsync();
            return;
        }
        await Application.Current.MainPage.DisplayAlert("Aviso", "Ocurrió un error al registrar el usuario", "Ok");
    }

    [RelayCommand]
    public async Task ReturnLogin() => await Shell.Current.Navigation.PopAsync();
}
