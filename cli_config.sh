sudo dnf update && sudo dnf upgrade

git clone https://github.com/zsh-users/zsh-autosuggestions ~/.zsh/zsh-autosuggestions
git clone https://github.com/zsh-users/zsh-syntax-highlighting.git  ~/.zsh/zsh-syntax-highlighting
mkdir ~/.zsh
mkdir ~/.config/alacritty
cp ~/Documents/dotfiles/promt.sh ~/.zsh/
cp ~/Documents/dotfiles/.zshrc ~/
cp ~/Documents/dotfiles/.tmux.conf ~/
cp ~/Documents/dotfiles/alacritty.yml ~/.config/alacritty/
cp ~/Documents/dotfiles/.gitconfig ~/

mkdir ~/.local/share/fonts
unzip ~/Documents/dotfiles/JetBrainsMono.zip -d ~/.local/share/fonts/
fc-cache ~/.local/share/fonts

