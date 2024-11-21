using BarberApp.Movil.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using System.Net.Http.Json;

namespace BarberApp.Movil.ViewModels;
public partial class LoginViewModel : ObservableObject
{
    readonly HttpClient httpClient = new();

    [ObservableProperty]
    private string _email;

    [ObservableProperty]
    private string _password;

    [RelayCommand]
    public async Task Login()
    {
        var loginUser = new LoginUsuario
        {
            Email = Email,
            Password = Password,
        };
        var result = await httpClient.PostAsJsonAsync(Settings.Settings.UrlApi + "/login.php?tipo=usuario", loginUser);
        if (result.IsSuccessStatusCode)
        {
            var user = await result.Content.ReadFromJsonAsync<Usuario>();
            DatosSesion.IdUsuario = user.IdUsuario;
            DatosSesion.NombreUsuario = user.Nombres;
            DatosSesion.TelefonoUsuario = user.Telefono;
            DatosSesion.FotoUsuario = user.Foto;
            await Shell.Current.GoToAsync("//mainPage");
            return;
        }
        await Application.Current.MainPage.DisplayAlert("Error", "El usuario y/o contraseña son inválidos", "Ok");
    }

    [RelayCommand]
    public async Task GoRegister()
    {
        await Shell.Current.GoToAsync("register");
    }
}
