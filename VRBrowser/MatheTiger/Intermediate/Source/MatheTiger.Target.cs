using UnrealBuildTool;

public class MatheTigerTarget : TargetRules
{
	public MatheTigerTarget(TargetInfo Target) : base(Target)
	{
		Type = TargetType.Game;
		ExtraModuleNames.Add("MatheTiger");
	}
}
