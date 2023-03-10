source ~/.zsh/promt.sh
source /usr/share/zsh/plugins/zsh-autosuggestions/zsh-autosuggestions.zsh
source /usr/share/zsh/plugins/zsh-syntax-highlighting/zsh-syntax-highlighting.zsh
autoload -U compinit; compinit

alias ll="exa -hla --git --icons"
alias ls="exa"
alias vim="nvim"
alias vi="nvim"
alias tree="exa --long --header --git --icons --tree --level=4 -a -I=.git --git-ignore"
alias zshrc="vim ~/.zshrc"
alias zshrcs="source ~/.zshrc"
alias vimrc="vim ~/.config/nvim/init.lua"
alias vimrcs="source ~/.config/nvim/init.lua"
alias tmuxrc="vim ~/.tmux.conf"
alias gc='git commit -m' 
alias gca='git commit --amend -m' 
alias gs='git status -sb' 
alias ga='git add' 
alias gco='git checkout' 
alias gl='git pull origin' 
alias gp='git push origin' 
alias gst='git stash' 
alias grhh='git reset --hard HEAD' 
alias dcc='docker-compose' 
alias gb='git branch' 
alias d='docker' 
alias di='docker images'
alias dps='docker ps -a'


cursor_mode() {
    # See https://ttssh2.osdn.jp/manual/4/en/usage/tips/vim.html for cursor shapes
    cursor_block='\e[2 q'
    cursor_beam='\e[6 q'

    function zle-keymap-select {
        if [[ ${KEYMAP} == vicmd ]] ||
            [[ $1 = 'block' ]]; then
            echo -ne $cursor_block
        elif [[ ${KEYMAP} == main ]] ||
            [[ ${KEYMAP} == viins ]] ||
            [[ ${KEYMAP} = '' ]] ||
            [[ $1 = 'beam' ]]; then
            echo -ne $cursor_beam
        fi
    }

    zle-line-init() {
        echo -ne $cursor_beam
    }

    zle -N zle-keymap-select
    zle -N zle-line-init
}

cursor_mode


export PATH="$HOME/.yarn/bin:$HOME/.config/yarn/global/node_modules/.bin:$Home/.local/bin:$PATH"
export PATH="$HOME/.local/bin:$PATH"
export GIT_EDITOR='vim'
export VISUAL='vim'
export EDITOR='vim'



if [ $(command -v "fzf") ]; then
    source /usr/share/fzf/key-bindings.zsh
fi
export FZF_DEFAULT_COMMAND="rg --files --hidden --glob '!.git'"
export FZF_CTRL_T_COMMAND="$FZF_DEFAULT_COMMAND"





