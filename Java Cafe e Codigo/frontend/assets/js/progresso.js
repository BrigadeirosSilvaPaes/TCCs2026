async function salvarProgresso(modulo, nivel, xp) {

    await fetch("/backend/progresso/salvar_progresso.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            modulo: modulo,
            nivel: nivel,
            xp: xp
        })
    });
}
async function buscarProgresso(modulo) {

    let res = await fetch("/backend/progresso/buscar_progresso.php?modulo=" + modulo);
    return await res.json();
}